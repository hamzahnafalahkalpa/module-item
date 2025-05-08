<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\LaravelSupport\Contracts\Data\PaginateData;
use Hanafalah\ModuleItem\Contracts\Schemas\{
    Item as ContractsItem
};
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleItem\Contracts\Data\ItemData;

class Item extends PackageManagement implements ContractsItem
{
    protected string $__entity = 'Item';
    public static $item_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'item',
            'tags'     => ['item', 'item-index'],
            'duration' => 60 * 12
        ]
    ];

    // private function localAddSuffixCache(mixed $suffix): void{
    //     $this->addSuffixCache($this->__cache['index'], "item-index", $suffix);
    // }

    protected function createItem(ItemData $item_dto): Model{
        if (isset($item_dto->id)) {
            $guard = ['id' => $item_dto->id];
        } else {
            $guard = [
                'reference_id'   => $item_dto->reference_id,
                'reference_type' => $item_dto->reference_type
            ];
        }

        $item                      = $this->ItemModel()->where($guard)->firstOrCreate($guard);
        $current_cogs              = $item->cogs;
        $current_selling_price     = $item->selling_price;
        $item->name                = $item_dto->name;
        $item->barcode             = $item_dto->barcode ?? null;
        $item->unit_id             = $item_dto->unit_id;
        $item->net_unit_id         = $item_dto->net_unit_id ?? null;
        $item->net_qty             = $item_dto->net_qty ?? null;
        $item->cogs                = $item_dto->cogs ?? 0;
        $item->margin              = $item_dto->margin ?? 0;
        $item->min_stock           = $item_dto->min_stock ?? 150;
        $item->is_using_batch      = $item_dto->is_using_batch ?? false;
        $item->tax                 = $item_dto->tax ?? 0;
        $item->netto               = $item_dto->netto ?? null;
        $item->last_selling_price  = $item_dto->last_selling_price ?? $current_selling_price ?? 0;
        $item->last_cogs           = $item_dto->last_cogs ?? $current_cogs ?? 0;
        
        if (isset($item_dto->selling_price)) $item->selling_price = $item_dto->selling_price ?? 0;        
        $this->fillingProps($item, $item_dto->props);
        // foreach ($item_dto->props as $key => $prop) $item->{$key} = $prop;
        $item->save();
        return $item;
    }

    protected function processItemStock(ItemData &$item_dto, &$item){
        $item_stock_dto = $item_dto->item_stock;
        if (isset($item_stock_dto->warehouse_id) && isset($item_stock_dto->warehouse_type)) {
            if (isset($item_stock_dto->stock) || (isset($item_stock_dto->stock_batches) && count($item_stock_dto->stock_batches) > 0)) {
                if ($item_dto->is_using_batch) {
                    if (!isset($item_stock_dto->stock_batches) || count($item_stock_dto->stock_batches) == 0) {
                        throw new \Exception('No stock batches provided', 422);
                    }
                }
                $funding = $this->FundingModel()->where('props->is_self', true)->first();
                if (!isset($funding)) throw new \Exception('No funding provided', 422);

                $item_stock_schema            = $this->schemaContract('item_stock');
                $item_stock_dto->funding_id   = $funding->getKey();
                $item_stock_dto->subject_type = $item->getMorphClass();
                $item_stock_dto->subject_id   = $item->getKey();
                $item_stock_schema->prepareStoreItemStock($item_stock_dto);
            }
        }
        $item->load('itemStocks');
    }

    protected function processComposition(ItemData &$item_dto, Model &$item){
        $composition_schema = $this->schemaContract('composition');
        $item->compositions()->detach();
        $compositions = [];
        foreach ($item_dto->compositions as $composition) {
            $composition = $composition_schema->prepareStoreComposition($composition);
            $compositions[] = $composition;
        }
        $item->compositions()->attach($compositions, [
            'model_type' => $item->getMorphClass()
        ]);
        $item->is_has_composition = true;
        $item->composition_total = count($item_dto->compositions);
    }

    public function prepareStoreItem(ItemData $item_dto): Model{
        $item = $this->createItem($item_dto);
        if (isset($item_dto->item_stock)) {
            $this->processItemStock($item_dto,$item);            
        }

        if (isset($item_dto->compositions) && count($item_dto->compositions) > 0) {
            $this->processComposition($item_dto,$item);            
        } else {
            $item->is_has_composition = false;
            $item->compositions()->detach();
        }
        $this->fillingProps($item,$item_dto->props);
        $item->save();
        static::$item_model = $item;
        return $item;
    }

    // public function storeItem(?ItemData $item_dto = null): array{
    //     return $this->transaction(function() use ($item_dto){
    //         return $this->showItem($this->prepareStoreItem($item_dto ?? $this->requestDTO(ItemData::class)));
    //     });
    // }

    // public function prepareShowItem(?Model $model = null, ?array $attributes = null): Model{
    //     $model ??= $this->getItem();
    //     if (!isset($model)) {
    //         $id = request()->id;
    //         if (!request()->has('id')) throw new \Exception('No id provided', 422);
    //         $model = $this->item()->with($this->showUsingRelation())->find($id);
    //     } else {
    //         $model->load($this->showUsingRelation());
    //     }
    //     return static::$item_model = $model;
    // }

    // public function showItem(?Model $model = null): array{
    //     return $this->showEntityResource(function() use ($model){
    //         return $this->prepareShowItem($model);
    //     });
    // }

    // public function prepareViewItemPaginate(mixed $cache_reference_type, ?array $morphs = null, PaginateData $paginate_dto): LengthAwarePaginator{
    //     $morphs              ??= $cache_reference_type;
    //     $cache_reference_type .= '-paginate';
    //     $this->localAddSuffixCache($cache_reference_type);
    //     return $this->cacheWhen(!$this->isSearch() || !isset(request()->warehouse_id) || request()->type !== 'all', $this->__cache['index'], function () use ($paginate_dto) {
    //         return $this->item()->with($this->viewUsingRelation())->orderBy('name', 'asc')
    //                     ->paginate(...$paginate_dto->toArray())->appends(request()->all());
    //     });
    // }

    // public function viewItemPaginate(mixed $reference_type, ?array $morphs = null, ?PaginateData $paginate_dto = null): array{
    //     return $this->viewEntityResource(function() use ($reference_type, $morphs, $paginate_dto){
    //         return $this->prepareViewItemPaginate($reference_type, $morphs, $paginate_dto ?? $this->requestDTO(PaginateData::class));
    //     },['rows_per_page' => [50]]);
    // }

    // public function prepareFindItem(?array $attributes = null): mixed{
    //     $attributes ??= request()->all();
    //     $item = $this->item()->conditionals($this->mergeCondition([]))
    //         ->when(isset($attributes['transaction_id']), function ($query) use ($attributes) {
    //             $query->with([
    //                 'cardStock' => function ($query) use ($attributes) {
    //                     $query->with([
    //                         'stockMovements' => function ($query) use ($attributes) {
    //                             $query->with(['batchMovements.batch'])
    //                                 ->when(isset($attributes['direction']), function ($query) use ($attributes) {
    //                                     $query->where('direction', $attributes['direction']);
    //                                 });
    //                         }
    //                     ])->where('transaction_id', $attributes['transaction_id']);
    //                 }
    //             ]);
    //         })
    //         ->when(isset($attributes['id']), function ($query) use ($attributes) {
    //             if (is_array($attributes['id']) && count($attributes['id']) > 0) {
    //                 $query->whereIn('id', $attributes['id']);
    //             } else {
    //                 $query->where('id', $attributes['id']);
    //             }
    //         })
    //         ->with([
    //             'reference',
    //             'compositions'
    //         ])->orderBy('name', 'asc');
    //     $attributes['response_as'] ??= 'paginate';
    //     switch ($attributes['response_as']) {
    //         case 'single-data' : $item = $item->first();break;
    //         case 'collection'  : $item = $item->get();break;
    //         case 'paginate'    : $item = $item->paginate($attributes['per_page'] ?? 10)->appends(request()->all());break;
    //     }

    //     return static::$item_model = $item;
    // }

    public function item(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->ItemModel()->when(isset(request()->warehouse_id), function ($query) {
                    $warehouse = $this->{config('module-warehouse.warehouse').'Model'}()->findOrFail(request()->warehouse_id);
                    $query->whereHas('itemStock', function ($query) use ($warehouse) {
                        $query->where('warehouse_id', $warehouse->getKey())->where('warehouse_type', $warehouse->getMorphClass());
                    })->with([
                        'itemStock' => function ($query) use ($warehouse) {
                            $query->whereNull('funding_id')->where('warehouse_id', $warehouse->getKey())
                                ->where('warehouse_type', $warehouse->getMorphClass());
                            if (!isset(request()->non_batch)) $query->with('stockBatches.batch');
                            if (!isset(request()->non_funding)) {
                                $query->with([
                                    'childs' => function ($query) {
                                        $query->with(['funding', 'stockBatches.batch']);
                                    }
                                ]);
                            }
                        }
                    ]);
                })
                ->when(isset(request()->search_reference_type), function ($query) {
                    $type = Str::studly(request()->search_reference_type);
                    $query->where('reference_type', $type);
                })
                ->withParameters()
                ->conditionals($this->mergeCondition($conditionals ?? []))
                ->orderBy('name', 'asc');
    }
}
