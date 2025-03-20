<?php

namespace Gii\ModuleItem\Schemas;

use Gii\ModuleItem\Contracts\{
    CardStock as ContractsCardStock
};
use Gii\ModuleItem\Resources\CardStock\{
    ShowCardStock, ViewCardStock
};
use Illuminate\Database\Eloquent\{
    Builder, Collection, Model
};
use Illuminate\Pagination\LengthAwarePaginator;
use Zahzah\LaravelSupport\Supports\PackageManagement;
use Zahzah\ModuleDistribution\Enums\Distribution\Flag;

class CardStock extends PackageManagement implements ContractsCardStock{
    protected array $__guard   = ['id'];
    protected array $__add     = [];
    protected string $__entity = 'CardStock';
    public static $card_stock_model;

    protected array $__resources = [
        'view' => ViewCardStock::class,
        'show' => ShowCardStock::class
    ];

    public function prepareStoreCardStock(? array $attributes = null): Model{
        $attributes ??= request()->all();
        if (isset($attributes['id'])){
            $guard = ['id' => $attributes['id']];
        }else{
            if (!isset($attributes['item_id'])) throw new \Exception('item_id is required');
            if (!isset($attributes['transaction_id'])) throw new \Exception('transaction_id is required');

            $guard = [
                'parent_id'      => $attributes['parent_id'] ?? null,
                'item_id'        => $attributes['item_id'],
                'transaction_id' => $attributes['transaction_id']
            ];
        }
        $card_stock = $this->CardStockModel()->firstOrCreate($guard);
        if (isset($attributes['is_procurement'])) $card_stock->is_procurement = $attributes['is_procurement'];
        if (isset($attributes['margin'])) $card_stock->margin = intval($attributes['margin'] ?? 0);
        
        $card_stock->load('transactionItem');
        $card_stock->total_tax  = 0;
        $card_stock->total_cogs = 0;
        $transaction = $card_stock->transaction;
        $transaction->load('reference');
        if (!isset($attributes['stock_movements']) && isset($attributes['stock_movement'])) $attributes['stock_movements'] = [$attributes['stock_movement']];
        if (isset($attributes['stock_movements']) && count($attributes['stock_movements'])){
            foreach ($attributes['stock_movements'] as $stock_movement) {
                $stock_movement['direction'] ??= $attributes['direction'];
                $item_model = $this->ItemModel()->findOrFail($card_stock->item_id);

                $this->isNeedParent($stock_movement,$transaction);

                $card_stock->total_qty ??= 0;
                if (isset($stock_movement['goods_receipt_unit'])){
                    $stock_movement['goods_receipt_unit']['card_stock_id'] = $card_stock->getKey();
                    $goods = $this->schemaContract('goods_receipt_unit')->prepareStoreGoodsReceiptUnit($stock_movement['goods_receipt_unit']);
                    if (isset($stock_movement['cogs'])){
                        $total_cogs                   = $stock_movement['cogs'] * $stock_movement['goods_receipt_unit']['unit_qty'];
                        $stock_movement['total_cogs'] = $total_cogs;
                        $goods->cogs                  = $stock_movement['cogs'];
                        $goods->total_cogs            = $total_cogs;
                        $goods->save();
                    }
                    $stock_movement['goods_receipt_unit_id'] = $goods->getKey();
                }else{
                    $card_stock->total_qty += $stock_movement['qty'] ?? 0;
                }
                if (isset($stock_movement['item_stock_id'])){
                    $item_stock = $this->ItemStockModel()->findOrFail($stock_movement['item_stock_id']);
                    $stock_movement['funding_id'] = $item_stock->funding_id ?? null;
                }
                $stock_movement_model = $this->schemaContract('stock_movement')->prepareStoreStockMovement([
                    'warehouse_id'          => $attributes['warehouse_id'] ?? $stock_movement['warehouse_id'] ?? null,
                    'warehouse_type'        => $attributes['warehouse_type'] ?? $stock_movement['warehouse_type'] ?? null,
                    'funding_id'            => $attributes['funding_id'] ?? $stock_movement['funding_id'] ?? null,
                    'direction'             => $attributes['direction'] ?? $stock_movement['direction'],
                    'card_stock_id'         => $card_stock->getKey(),
                    'id'                    => $stock_movement['id'] ?? null,
                    'parent_id'             => $stock_movement['parent_id'] ?? null,
                    'reference_type'        => $stock_movement['reference_type'] ?? null,
                    'reference_id'          => $stock_movement['reference_id'] ?? null,
                    'qty'                   => $stock_movement['qty'] ?? null,
                    'margin'                => $stock_movement['margin'] ?? $attributes['margin'] ?? $item_model->margin ?? 0,
                    'batch_movements'       => $stock_movement['batch_movements'] ?? [],
                    'goods_receipt_unit_id' => $stock_movement['goods_receipt_unit_id'] ?? null,
                    'price'                 => $stock_movement['price'] ?? null
                ]);

                if (isset($stock_movement['cogs'])){
                    $stock_movement_model->cogs = $stock_movement['cogs'];
                    $stock_movement_model->total_cogs = $stock_movement['total_cogs'] ?? $stock_movement['cogs'] ?? null;
                    $stock_movement_model->save();

                    $card_stock->total_cogs += $stock_movement['cogs'];
                }                
            }
        }else{
            if (isset($attributes['cogs'])) $card_stock->total_cogs = $attributes['cogs'];
        }
        if (isset($attributes['tax'])){
            $card_stock->total_tax += $card_stock->total_cogs*($attributes['tax']/100);
        }
        $card_stock->save();
        return static::$card_stock_model = $card_stock;
    }

    private function isNeedParent($stock_movement,$transaction): void{
        $is_need_parent_id = in_array($transaction->reference_type,[$this->DistributionModel()->getMorphClass()]);
        if ($is_need_parent_id && $stock_movement['direction'] == $this->MainMovementModel()::OUT){
            if ($transaction->reference->flag == Flag::ORDER_DISTRIBUTION->value){
                if (!isset($stock_movement['parent_id'])) throw new \Exception('parent_id is required for distribution using out direction',422);
            }
        }
    }

    public function storeCardStock(): array {
        return $this->transaction(function(){
            return $this->showCardStock($this->prepareStoreCardStock());
        });
    }

    public function showUsingRelation(): array{
        return ['goodsReceiptUnit','stockMovements' => function($query){
            $query->with([
                'reference','batchMovements',
                'itemStock'
            ]);
        }];
    }

    public function prepareShowCardStock(? Model $model = null, ? array $attributes = null): Model{
        $attributes ??= request()->all();
        $model ??= $this->getCardStock();
        if (!isset($model)){
            $id = request()->id;
            if (!request()->has('id')) throw new \Exception('No id provided',422);
            $model = $this->cardStock()->with($this->showUsingRelation())->find($id);
        }else{
            $model->load($this->showUsingRelation());
        }

        return static::$card_stock_model = $model;
    }

    public function showCardStock(? Model $model = null): array{
        return $this->transforming($this->__resources['show'],function() use ($model){
            return $this->prepareShowCardStock($model);
        });
    }

    public function prepareViewCardStockList(? array $attributes = null): Collection{
        $attributes ??= request()->all();

        $model = $this->cardStock()->with($this->showViewUsingRelation())
                      ->orderBy('reported_at','desc')->get();

        return static::$card_stock_model = $model;
    }

    public function viewCardStockList(): array{
        return $this->transforming($this->__resources['view'],function(){
            return $this->prepareViewStockList();
        });
    }

    public function prepareViewCardStockPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page',? int $page = null,? int $total = null): LengthAwarePaginator{
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');

        $attributes ??= request()->all();
        if (!isset($attributes['item_id'])) throw new \Exception('item_id is required');

        $model = $this->cardStock()->with([
                    'transaction',
                    'stockMovement' => function($query) use ($attributes){
                        $query->with([
                            'batchMovements.batch',
                            'childs.batchMovements.batch',
                        ])->when(isset($attributes['warehouse_id']),function($query) use ($attributes){
                            $query->hasWarehouse($attributes['warehouse_id']);
                        });
                    }
                ])
                ->whereNotNull('reported_at')
                ->where('item_id',$attributes['item_id'])
                ->when(isset($attributes['warehouse_id']),function($query) use ($attributes){
                    $query->whereHas('stockMovement',function($query) use ($attributes){
                        $query->hasWarehouse($attributes['warehouse_id']);
                    });
                })
                ->orderBy('reported_at','desc')
                ->paginate(...$this->arrayValues($paginate_options));
        return static::$card_stock_model = $model;
    }


    public function viewCardStockPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page',? int $page = null,? int $total = null): array{
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');
        return $this->transforming($this->__resources['view'],function() use ($paginate_options){
            return $this->prepareViewCardStockPaginate(...$this->arrayValues($paginate_options));
        });
    }

    public function getCardStock(): mixed{
        return static::$card_stock_model;
    }

    public function cardStock(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->CardStockModel()->conditionals($conditionals)->withParameters();
    }
}
