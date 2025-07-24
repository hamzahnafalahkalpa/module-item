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
use Hanafalah\ModuleItem\Contracts\Data\CardStockData;
use Hanafalah\ModuleTax\Concerns\HasTaxCalculation;
use Hanafalah\ModuleWarehouse\Contracts\Data\StockMovementData;
use Hanafalah\ModuleWarehouse\Enums\MainMovement\Direction;

class CardStock extends PackageManagement implements ContractsCardStock
{
    use HasTaxCalculation;

    protected string $__entity = 'CardStock';
    public $card_stock_model;
    protected mixed $__order_by_created_at = ['reported_at', 'desc']; //asc, desc, false

    protected function createCardStock(CardStockData &$card_stock_dto){
        if (!isset($card_stock_dto->item_id) && isset($card_stock_dto->item)){
            $item_model = $this->schemaContract('item')->prepareStoreItem($card_stock_dto->item);
            $card_stock_dto->item_id = $item_model->getKey();
            $card_stock_dto->props->props['prop_item'] = $item_model->toViewApi()->resolve();
            foreach ($card_stock_dto->stock_movements as &$stock_movement) {
                $stock_movement->qty_unit_id ??= $item_model->unit_id;
                $stock_movement->props->props['prop_unit'] = $item_model->prop_unit;
            }
        }

        $add = [
            'parent_id'      => $card_stock_dto->parent_id ?? null,
            'item_id'        => $card_stock_dto->item_id,
            'transaction_id' => $card_stock_dto->transaction_id,
            'reference_id'   => $card_stock_dto->reference_id,
            'reference_type' => $card_stock_dto->reference_type
        ];
        if (isset($card_stock_dto->id)) {
            $guard  = ['id' => $card_stock_dto->id];
            $create = [$guard, $add];
        } else {
            $create = [$add];
        }
        $card_stock = $this->usingEntity()->firstOrCreate(...$create);
        // if (isset($props['warehouse_id'])){
        //     $warehouse = $this->{config('module-item.warehouse').'Model'}()->findOrFail($props['warehouse_id']);
        //     $props['prop_warehouse'] = [
        //         'id'   => $warehouse->getKey(),
        //         'name' => $warehouse->name
        //     ];
        // }

        $card_stock->load(['transactionItem','transaction.reference']);
        if (count($card_stock_dto->stock_movements) == 0 && isset($card_stock_dto->stock_movement)) {
            $card_stock_dto->stock_movements = [$card_stock_dto->stock_movement];
            foreach ($card_stock_dto->stock_movements as &$stock_movement) {
                $stock_movement->card_stock_model = &$card_stock;
            }
            $card_stock_dto->stock_movement = null;
        }else{
            // $this->taxCalculation($props['tax']);
        }
        return $card_stock;
    }

    protected function createGoodsReceiptUnit(StockMovementData &$stock_movement){
        $goods = $this->schemaContract('goods_receipt_unit')->prepareStoreGoodsReceiptUnit($stock_movement->goods_receipt_unit);
        if (isset($stock_movement->props->cogs)) {
            $stock_movement->props->props['total_cogs'] = $total_cogs = $stock_movement->props->cogs * $goods->unit_qty;;
            $goods->cogs  = $stock_movement->props->cogs;
            $goods->total_cogs = $total_cogs;
            $goods->save();
        }
        $stock_movement->goods_receipt_unit_id = $goods->getKey();
    }

    protected function storeMappingStockMovement(CardStockData &$card_stock_dto, Model $card_stock){
        $transaction = $card_stock->transaction;
        $props       = &$card_stock_dto->props->props;
        foreach ($card_stock_dto->stock_movements as $stock_movement_dto) {
            $stock_movement_dto->direction ??= $props['direction'] ?? 'REQUEST';

            $card_stock_dto->total_qty = 0;
            if (isset($stock_movement_dto->goods_receipt_unit)) {
                $stock_movement_dto->goods_receipt_unit->card_stock_id = $card_stock->getKey();
                $this->createGoodsReceiptUnit($stock_movement_dto);
            } else {
                $card_stock_dto->total_qty += $stock_movement_dto->qty ?? 0;
            }
            if ($stock_movement_dto->direction == Direction::IN->value) {
                $card_stock_dto->receive_qty = $stock_movement_dto->qty ?? 0;
            }
            
            $stock_movement_dto->card_stock_id = $card_stock->getKey();
            if (isset($stock_movement_dto->item_stock)){
                $item_stock_dto = &$stock_movement_dto->item_stock;
                $item_stock_dto->procurement_id ??= $card_stock->reference_id;
                $item_stock_dto->procurement_type ??= $card_stock->reference_type;
            }
            $stock_movement_model = $this->schemaContract('stock_movement')->prepareStoreStockMovement($stock_movement_dto);
            if (isset($stock_movement_dto->props->cogs)) {
                $stock_movement_model->cogs       = $stock_movement_dto->props->cogs;
                $stock_movement_model->total_cogs = $stock_movement_dto->props->total_cogs ?? ($stock_movement_dto->props->cogs * $stock_movement_dto->qty) ?? null;
                $stock_movement_model->save();
                $card_stock_dto->total_cogs+= $stock_movement_model->total_cogs;
            }
        }
    }

    public function prepareStoreCardStock(CardStockData $card_stock_dto): Model{
        $card_stock  = $this->createCardStock($card_stock_dto);
        $this->storeMappingStockMovement($card_stock_dto, $card_stock);
        if (isset($card_stock_dto->props->props['tax'])){
            $card_stock_dto->total_tax = intval($card_stock_dto->props->props['tax']->ppn/100 * $card_stock_dto->total_cogs);
        }
        $this->fillingProps($card_stock, $card_stock_dto->props);
        $card_stock->receive_qty = floatval($card_stock_dto->receive_qty);
        $card_stock->request_qty = floatval($card_stock_dto->request_qty);
        $card_stock->total_qty   = $card_stock_dto->total_qty;
        $card_stock->total_tax   = $card_stock_dto->total_tax;
        $card_stock->total_cogs  = $card_stock_dto->total_cogs;
        $card_stock->save();
        return $this->card_stock_model = $card_stock;
    }

    public function cardStock(mixed $conditionals = null): Builder{
        return $this->generalSchemaModel($conditionals)
                    ->when(isset(request()->search_warehouse_id), function ($query) {
                        $query->whereHas('stockMovement', function ($query) {
                            $query->hasWarehouse(request()->search_warehouse_id);
                        });
                    });
    }
}
