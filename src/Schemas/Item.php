<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\{
    Item as ContractsItem
};
use Hanafalah\ModuleItem\Resources\Item\{
    ShowItem,
    ViewItem
};
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class Item extends PackageManagement implements ContractsItem
{
    protected array $__guard   = ['id'];
    protected array $__add     = ['name', 'flag', 'parent_id'];
    protected string $__entity = 'Item';
    public static $item_model;

    protected array $__resources = [
        'view' => ViewItem::class,
        'show' => ShowItem::class
    ];

    protected array $__cache = [
        'index' => [
            'name'     => 'item',
            'tags'     => ['item', 'item-index'],
            'duration' => 60 * 12
        ]
    ];

    private function localAddSuffixCache(mixed $suffix): void
    {
        $this->addSuffixCache($this->__cache['index'], "item-index", $suffix);
    }

    public function prepareStoreItem(?array $attributes = null): Model
    {
        $attributes ??= request()->all();
        $attributes['is_using_batch'] ??= false;

        if (isset($attributes['id'])) {
            $guard = ['id' => $attributes['id']];
        } else {
            $guard = [
                'reference_id' => $attributes['reference_id'],
                'reference_type' => $attributes['reference_type']
            ];
        }

        $item = $this->ItemModel()->where($guard)->firstOrCreate($guard);
        $current_cogs              = $item->cogs;
        $current_selling_price     = $item->selling_price;
        $item->name                = $attributes['name'];
        $item->barcode             = $attributes['barcode'] ?? null;
        $item->unit_id             = $attributes['unit_id'];
        $item->net_unit_id         = $attributes['net_unit_id'] ?? null;
        $item->net_qty             = $attributes['net_qty'] ?? null;
        $item->cogs                = $attributes['cogs'];
        $item->margin              = $attributes['margin'];
        $item->min_stock           = $attributes['min_stock'];
        $item->is_using_batch      = $attributes['is_using_batch'];
        $item->tax                 = $attributes['tax'];
        $item->netto               = $attributes['netto'] ?? null;
        $item->last_selling_price  = $attributes['last_selling_price'] ?? $current_selling_price ?? 0;
        $item->last_cogs           = $attributes['last_cogs'] ?? $current_cogs ?? 0;

        if (isset($attributes['selling_price'])) {
            $item->selling_price = $attributes['selling_price'];
        }

        if (isset($attributes['net_unit_id'])) {
            $item_stuff = $this->ItemStuffModel()->findOrFail($attributes['net_unit_id']);
            $item->net_unit_name = $item_stuff->name;
        }

        if (isset($attributes['item_stock'])) {
            $item_stock_attr = $attributes['item_stock'];
            if (isset($item_stock_attr['warehouse_id']) && isset($item_stock_attr['warehouse_type'])) {
                if (isset($item_stock_attr['stock']) || (isset($item_stock_attr['stock_batches']) && count($item_stock_attr['stock_batches']) > 0)) {
                    if ($attributes['is_using_batch']) {
                        if (!isset($item_stock_attr['stock_batches']) || count($item_stock_attr['stock_batches']) == 0) {
                            throw new \Exception('No stock batches provided', 422);
                        }
                    }
                    $funding = $this->FundingModel()->where('props->is_self', true)->first();
                    if (!isset($funding)) throw new \Exception('No funding provided', 422);

                    $item_stock_schema = $this->schemaContract('item_stock');
                    $item_stock_attr['funding_id']   = $funding->getKey();
                    $item_stock_attr['subject_type'] = $item->getMorphClass();
                    $item_stock_attr['subject_id']   = $item->getKey();
                    $item_stock_schema->prepareStoreItemStock($item_stock_attr);
                }
            }
            $item->load('itemStocks');
        }

        if (isset($attributes['compositions']) && count($attributes['compositions']) > 0) {
            $composition_schema = $this->schemaContract('composition');
            $item->compositions()->detach();
            $compositions = [];
            foreach ($attributes['compositions'] as $composition) {
                $composition = $composition_schema->prepareStoreComposition($composition);
                $compositions[] = $composition;
            }
            $item->compositions()->attach($compositions, [
                'model_type' => $item->getMorphClass()
            ]);
            $item->is_has_composition = true;
            $item->composition_total = count($attributes['compositions']);
        } else {
            $item->is_has_composition = false;
            $item->compositions()->detach();
        }
        if (isset($attributes['jurnal'])) {
            $item->jurnal = $attributes['jurnal'];
        }
        $item->save();
        static::$item_model = $item;
        return $item;
    }

    public function storeItem(): array
    {
        return $this->transaction(function () {
            return $this->showItem($this->prepareStoreItem());
        });
    }

    protected function showUsingRelation()
    {
        return ['reference', 'itemStock' => function ($query) {
            $query->whereNull('funding_id')->with([
                'stockBatches.batch',
                'childs.stockBatches.batch'
            ]);
        }];
    }

    public function prepareShowItem(?Model $model = null): Model
    {
        $this->booting();

        $model ??= $this->getItem();
        if (!isset($model)) {
            $id = request()->id;
            if (!request()->has('id')) throw new \Exception('No id provided', 422);
            $model = $this->item()->with($this->showUsingRelation())->find($id);
        } else {
            $model->load($this->showUsingRelation());
        }
        return static::$item_model = $model;
    }

    public function showItem(?Model $model = null): array
    {
        return $this->transforming($this->__resources['show'], function () use ($model) {
            return $this->prepareShowItem($model);
        });
    }

    public function prepareViewItemPaginate(mixed $cache_reference_type, ?array $morphs = null, int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): LengthAwarePaginator
    {
        $morphs ??= $cache_reference_type;
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');
        $cache_reference_type .= '-paginate';
        $this->localAddSuffixCache($cache_reference_type);
        return $this->cacheWhen(!$this->isSearch() || !isset(request()->warehouse_id) || request()->type !== 'all', $this->__cache['index'], function () use ($morphs, $paginate_options) {
            return $this->item()->orderBy('name', 'asc')
                ->paginate(...$this->arrayValues($paginate_options))
                ->appends(request()->all());
        });
    }

    public function viewItemPaginate(mixed $reference_type, ?array $morphs = null, int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): array
    {
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');
        return $this->transforming($this->__resources['view'], function () use ($reference_type, $morphs, $paginate_options) {
            return $this->prepareViewItemPaginate($reference_type, $morphs, ...$this->arrayValues($paginate_options));
        }, [
            'rows_per_page' => [50]
        ]);
    }

    public function prepareFindItem(?array $attributes = null): mixed
    {
        $attributes ??= request()->all();
        $item = $this->item()->conditionals($this->mergeCondition([]))
            ->when(isset($attributes['transaction_id']), function ($query) use ($attributes) {
                $query->with([
                    'cardStock' => function ($query) use ($attributes) {
                        $query->with([
                            'stockMovements' => function ($query) use ($attributes) {
                                $query->with(['batchMovements.batch'])
                                    ->when(isset($attributes['direction']), function ($query) use ($attributes) {
                                        $query->where('direction', $attributes['direction']);
                                    });
                            }
                        ])->where('transaction_id', $attributes['transaction_id']);
                    }
                ]);
            })
            ->when(isset($attributes['id']), function ($query) use ($attributes) {
                if (is_array($attributes['id']) && count($attributes['id']) > 0) {
                    $query->whereIn('id', $attributes['id']);
                } else {
                    $query->where('id', $attributes['id']);
                }
            })
            ->with([
                'reference',
                'compositions'
            ])->orderBy('name', 'asc');
        $attributes['response_as'] ??= 'paginate';
        switch ($attributes['response_as']) {
            case 'single-data':
                $item = $item->first();
                break;
            case 'collection':
                $item = $item->get();
                break;
            case 'paginate':
                $item = $item->paginate($attributes['per_page'] ?? 10)
                    ->appends(request()->all());
                break;
        }

        return static::$item_model = $item;
    }

    public function findItem(): mixed
    {
        $item = $this->prepareFindItem();
        if (!isset($item)) return null;
        return $this->transforming($this->__resources['show'], function () use ($item) {
            return $item;
        });
    }

    public function getItem(): mixed
    {
        return static::$item_model;
    }

    public function item(mixed $conditionals = null): Builder
    {
        $this->booting();
        return $this->ItemModel()->with('compositions')
            ->when(isset(request()->warehouse_id), function ($query) {
                $warehouse = app(config('module-warehouse.warehouse'))->findOrFail(request()->warehouse_id);
                $query->whereHas('itemStock', function ($query) use ($warehouse) {
                    $query->where('warehouse_id', $warehouse->getKey())
                        ->where('warehouse_type', $warehouse->getMorphClass());
                })->with(['itemStock' => function ($query) use ($warehouse) {
                    $query->whereNull('funding_id')->where('warehouse_id', $warehouse->getKey())
                        ->where('warehouse_type', $warehouse->getMorphClass());
                    if (!isset(request()->non_batch)) {
                        $query->with('stockBatches.batch');
                    }
                    if (!isset(request()->non_funding)) {
                        $query->with(['childs' => function ($query) {
                            $query->with(['funding', 'stockBatches.batch']);
                        }]);
                    }
                }]);
            })
            ->when(isset(request()->type), function ($query) {
                $type = Str::studly(request()->type);
                $query->where('reference_type', $type);
            })
            ->withParameters()
            ->conditionals($conditionals);
    }
}
