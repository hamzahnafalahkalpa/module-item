<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\ItemData as DataItemData;
use Hanafalah\ModuleWarehouse\Contracts\Data\StockMovementData;
use Hanafalah\ModuleWarehouse\Data\StockMovementData as DataStockMovementData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class GoodsReceiptUnitData extends Data implements DataItemData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('card_stock_id')]
    #[MapName('card_stock_id')]
    public mixed $card_stock_id;

    #[MapInputName('unit_id')]
    #[MapName('unit_id')]
    public mixed $unit_id = null;

    #[MapInputName('unit')]
    #[MapName('unit')]
    public mixed $unit = null;

    #[MapInputName('unit_qty')]
    #[MapName('unit_qty')]
    public float $unit_qty = 1;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(GoodsReceiptUnitData $data): GoodsReceiptUnitData{
        $data->props['prop_unit'] = [
            'id'    => $data->unit_id ?? null,
            'flag'  => null,
            'name'  => $data->unit['name'] ?? null
        ];
        if (isset($data->props['props_unit']['id'])){
            $unit = self::new()->ItemStuffModel()->findOrFail($data->props['props_unit']['id']);
            $data->props['props_unit']['flag']   = $unit->flag;
            $data->props['props_unit']['name'] ??= $unit->name;
        }
        return $data;
    }
}