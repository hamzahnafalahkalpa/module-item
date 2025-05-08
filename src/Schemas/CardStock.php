<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\{
    CardStock as ContractsCardStock
};
use Illuminate\Database\Eloquent\{
    Builder,
    Model
};
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleDistribution\Enums\Distribution\Flag;
use Hanafalah\ModuleItem\Contracts\Data\CardStockData;
use Hanafalah\ModuleTax\Concerns\HasTaxCalculation;
use Hanafalah\ModuleWarehouse\Contracts\Data\StockMovementData;
use Hanafalah\ModuleWarehouse\Enums\MainMovement\Direction;

class CardStock extends PackageManagement implements ContractsCardStock
{
    use HasTaxCalculation;

    protected string $__entity = 'CardStock';
    public static $card_stock_model;
    protected mixed $__order_by_created_at = ['reported_at', 'desc']; //asc, desc, false

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
        $props      = &$card_stock_dto->props->props;
        if (isset($card_stock_dto->reference_id)){
            $reference = $card_stock->reference;
            $props['prop_reference']['id']   = $reference->getKey();
            $props['prop_reference']['name'] = $reference->name;
        }
        if (isset($props['warehouse_id'])){
            $warehouse = $this->{config('module-item.warehouse').'Model'}()->findOrFail($props['warehouse_id']);
            $props['prop_warehouse'] = [
                'id'   => $warehouse->getKey(),
                'name' => $warehouse->name
            ];
        }

        $props['total_cogs'] = 0;
        $card_stock->load(['transactionItem','transaction.reference']);
        if (count($card_stock_dto->stock_movements) == 0 && isset($card_stock_dto->stock_movement)) {
            $card_stock_dto->stock_movements = [$card_stock_dto->stock_movement];
            $card_stock_dto->stock_movement = null;
        }else{
            // $this->taxCalculation($props['tax']);
        }
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

    protected function storeMappingStockMovement(CardStockData &$card_stock_dto, Model $card_stock){
        $transaction = $card_stock->transaction;
        $props = &$card_stock_dto->props->props;
        foreach ($card_stock_dto->stock_movements as $stock_movement_dto) {
            $stock_movement_dto->direction ??= $props['direction'];
            $this->isNeedParent($stock_movement_dto, $transaction);

            $props['total_qty'] = 0;
            if (isset($stock_movement_dto->goods_receipt_unit)) {
                $stock_movement_dto->goods_receipt_unit->card_stock_id = $card_stock->getKey();
                $this->createGoodsReceiptUnit($stock_movement_dto);
            } else {
                $props['total_qty'] += $stock_movement_dto->qty ?? 0;
            }
            if (isset($stock_movement_dto->item_stock_id)) $this->initFunding($stock_movement_dto);                
            
            $stock_movement_dto->card_stock_id = $card_stock->getKey();
            $stock_movement_model = $this->schemaContract('stock_movement')->prepareStoreStockMovement($stock_movement_dto);
            if (isset($stock_movement_dto->props->cogs)) {
                $stock_movement_model->cogs       = $stock_movement_dto->props->cogs;
                $stock_movement_model->total_cogs = $stock_movement_dto->props->total_cogs ?? ($stock_movement_dto->props->cogs * $stock_movement_dto->qty) ?? null;
                $stock_movement_model->save();
                $props['total_cogs'] += $stock_movement_model->total_cogs;
            }
        }
    }

    protected function initFunding(mixed &$stock_movement_dto){
        $item_stock = $this->ItemStockModel()->findOrFail($stock_movement_dto->item_stock_id);
        $stock_movement_dto->props->props->funding_id = $item_stock->funding_id ?? null;
        if (isset($stock_movement_dto->props->funding_id)) {
            $funding = $this->FundingModel()->findOrFail($stock_movement_dto->props->funding_id);
            $stock_movement_dto->props->props['prop_funding'] = [
                'id'   => $stock_movement_dto->props->funding_id,
                'name' => $funding->name
            ];
        }else{
            $stock_movement_dto->props->props['prop_funding'] = [
                'id'   => null,
                'name' => null
            ];
        }
    }

    public function prepareStoreCardStock(CardStockData $card_stock_dto): Model{
        $card_stock  = $this->createCardStock($card_stock_dto);
        $this->storeMappingStockMovement($card_stock_dto, $card_stock);
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

    public function cardStock(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->generalSchemaModel($conditionals)
                    ->when(isset(request()->warehouse_id), function ($query) {
                        $query->whereHas('stockMovement', function ($query) {
                            $query->hasWarehouse(request()->warehouse_id);
                        });
                    })->when(isset(request()->item_id), function($query){
                        $query->where('item_id', request()->item_id);
                    });
    }
}
