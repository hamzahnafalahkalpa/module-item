<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\{
    CardStock as ContractsCardStock
};
use Illuminate\Database\Eloquent\{
    Builder,
    Collection,
    Model
};
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleDistribution\Enums\Distribution\Flag;
use Hanafalah\ModuleItem\Contracts\Data\CardStockData;
use Hanafalah\ModuleWarehouse\Contracts\Data\StockMovementData;
use Hanafalah\ModuleWarehouse\Enums\MainMovement\Direction;

class CardStock extends PackageManagement implements ContractsCardStock
{
    protected string $__entity = 'CardStock';
    public static $card_stock_model;

    protected function createCardStock(CardStockData &$card_stock_dto){
        if (isset($card_stock_dto->id)) {
            $guard = ['id' => $card_stock_dto->id];
        } else {
            if (!isset($card_stock_dto->item_id))        throw new \Exception('item_id is required');
            if (!isset($card_stock_dto->transaction_id)) throw new \Exception('transaction_id is required');

            $guard = [
                'parent_id'      => $card_stock_dto->parent_id ?? null,
                'item_id'        => $card_stock_dto->item_id,
                'transaction_id' => $card_stock_dto->transaction_id,
                'reference_id'   => $card_stock_dto->reference_id,
                'reference_type' => $card_stock_dto->reference_type
            ];
        }
        $card_stock = $this->CardStockModel()->firstOrCreate($guard);

        // if (isset($card_stock_dto->props['is_procurement'])) $card_stock->is_procurement = $card_stock_dto->props['is_procurement'];
        // if (isset($card_stock_dto->props['margin']))         $card_stock->margin         = intval($card_stock_dto->props['margin'] ?? 0);

        $card_stock_dto->props['total_tax']  ??= 0;
        $card_stock_dto->props['total_cogs'] ??= 0;
        $card_stock->load(['transactionItem','transaction.reference']);
        if (!isset($card_stock_dto->stock_movements) && isset($card_stock_dto->stock_movement)) $card_stock_dto->stock_movements = [$card_stock_dto->stock_movement];
        return $card_stock;
    }

    protected function createGoodsReceiptUnit(StockMovementData &$stock_movement){
        $goods = $this->schemaContract('goods_receipt_unit')->prepareStoreGoodsReceiptUnit($stock_movement->goods_receipt_unit);
        if (isset($stock_movement->props['cogs'])) {
            // $total_cogs                          = $stock_movement->props['cogs'] * $stock_movement->goods_receipt_unit->unit_qty;
            $total_cogs                          = $stock_movement->props['cogs'] * $goods->unit_qty;
            $stock_movement->props['total_cogs'] = $total_cogs;
            $goods->cogs                         = $stock_movement->props['cogs'];
            $goods->total_cogs                   = $total_cogs;
            $goods->save();
        }
        $stock_movement->goods_receipt_unit_id = $goods->getKey();
    }

    public function prepareStoreCardStock(CardStockData $card_stock_dto): Model{
        $card_stock  = $this->createCardStock($card_stock_dto);
        $transaction = $card_stock->transaction;
        if (isset($card_stock_dto->stock_movements) && count($card_stock_dto->stock_movements)) {
            foreach ($card_stock_dto->stock_movements as $stock_movement_dto) {
                $stock_movement_dto->direction ??= $card_stock_dto->props['direction'];
                $item_model = $this->ItemModel()->findOrFail($card_stock->item_id);

                $this->isNeedParent($stock_movement_dto, $transaction);

                $card_stock_dto->props['total_qty'] ??= 0;
                if (isset($stock_movement_dto->goods_receipt_unit)) {
                    $stock_movement_dto->goods_receipt_unit->card_stock_id = $card_stock->getKey();
                    $this->createGoodsReceiptUnit($stock_movement_dto);
                } else {
                    $card_stock_dto->props['total_qty'] += $stock_movement_dto->qty ?? 0;
                }
                if (isset($stock_movement_dto->item_stock_id)) {
                    $item_stock = $this->ItemStockModel()->findOrFail($stock_movement_dto->item_stock_id);
                    $stock_movement_dto->funding_id = $item_stock->funding_id ?? null;
                    if (isset($stock_movement_dto->funding_id)){
                        $funding = $this->FundingModel()->findOrFail($stock_movement_dto->funding_id);
                        $stock_movement_dto->props['prop_funding'] = [
                            'id'   => $stock_movement_dto->funding_id,
                            'name' => $funding->name
                        ];
                    }
                }
                $stock_movement_model = $this->schemaContract('stock_movement')->prepareStoreStockMovement([
                    'warehouse_id'          => $card_stock_dto->props['warehouse_id'] ?? $stock_movement_dto->props['warehouse_id'] ?? null,
                    'warehouse_type'        => $card_stock_dto->props['warehouse_type'] ?? $stock_movement_dto->props['warehouse_type'] ?? null,
                    'funding_id'            => $card_stock_dto->props['funding_id'] ?? $stock_movement_dto->props['funding_id'] ?? null,
                    'direction'             => $card_stock_dto->props['direction'] ?? $stock_movement_dto->direction,
                    'card_stock_id'         => $card_stock->getKey(),
                    'id'                    => $stock_movement_dto->id ?? null,
                    'parent_id'             => $stock_movement_dto->parent_id ?? null,
                    'reference_type'        => $stock_movement_dto->reference_type ?? null,
                    'reference_id'          => $stock_movement_dto->reference_id ?? null,
                    'qty'                   => $stock_movement_dto->qty ?? null,
                    'margin'                => $stock_movement_dto->props['margin'] ?? $card_stock_dto->props['margin'] ?? $item_model->margin ?? 0,
                    'batch_movements'       => $stock_movement_dto->batch_movements ?? [],
                    'goods_receipt_unit_id' => $stock_movement_dto->goods_receipt_unit_id ?? null,
                    'price'                 => $stock_movement_dto->props['price'] ?? null
                ]);

                if (isset($stock_movement_dto->props['cogs'])) {
                    $stock_movement_model->cogs       = $stock_movement_dto->props['cogs'];
                    $stock_movement_model->total_cogs = $stock_movement_dto->props['total_cogs'] ?? $stock_movement_dto->props['cogs'] ?? null;
                    $stock_movement_model->save();

                    $card_stock_dto->props['total_cogs'] += $stock_movement_dto->props['cogs'];
                }
            }
        } else {
            if (isset($card_stock_dto->props['cogs'])) $card_stock_dto->total_cogs = $card_stock_dto->props['cogs'];
        }
        if (isset($card_stock_dto->props['tax'])) {
            $card_stock_dto->props['total_tax'] += $card_stock_dto->props['total_cogs'] * ($card_stock_dto->props['tax'] / 100);
        }
        $this->fillingProps($card_stock, $card_stock_dto->props);
        $card_stock->save();
        return static::$card_stock_model = $card_stock;
    }

    private function isNeedParent($stock_movement, $transaction): void{
        $is_need_parent_id = in_array($transaction->reference_type, ['Distribution']);
        if ($is_need_parent_id && $stock_movement['direction'] == Direction::OUT->value) {
            if ($transaction->reference->flag == Flag::ORDER_DISTRIBUTION->value) {
                if (!isset($stock_movement['parent_id'])) throw new \Exception('parent_id is required for distribution using out direction', 422);
            }
        }
    }

    public function storeCardStock(?CardStockData $card_stock_dto = null): array{
        return $this->transaction(function() use ($card_stock_dto) {
            return $this->showCardStock($this->prepareStoreCardStock($card_stock_dto ?? $this->requestDTO(CardStockData::class)));
        });
    }


    // public function prepareViewCardStockList(?array $attributes = null): Collection{
    //     $attributes ??= request()->all();
    //     $model = $this->cardStock()->with($this->showViewUsingRelation())->orderBy('reported_at', 'desc')->get();
    //     return static::$card_stock_model = $model;
    // }

    public function prepareViewCardStockPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): LengthAwarePaginator{
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');

        $attributes ??= request()->all();
        if (!isset($attributes['item_id'])) throw new \Exception('item_id is required');

        $model = $this->cardStock()->with([
            'transaction',
            'stockMovement' => function ($query) use ($attributes) {
                $query->with([
                    'batchMovements.batch',
                    'childs.batchMovements.batch',
                ])->when(isset($attributes['warehouse_id']), function ($query) use ($attributes) {
                    $query->hasWarehouse($attributes['warehouse_id']);
                });
            }
        ])
            ->whereNotNull('reported_at')
            ->where('item_id', $attributes['item_id'])
            ->when(isset($attributes['warehouse_id']), function ($query) use ($attributes) {
                $query->whereHas('stockMovement', function ($query) use ($attributes) {
                    $query->hasWarehouse($attributes['warehouse_id']);
                });
            })
            ->orderBy('reported_at', 'desc')
            ->paginate(...$this->arrayValues($paginate_options));
        return static::$card_stock_model = $model;
    }

    public function cardStock(mixed $conditionals = null): Builder
    {
        $this->booting();
        return $this->CardStockModel()->conditionals($this->mergeCondition($conditionals ?? []))->withParameters()->orderBy('repored_at','desc');
    }
}
