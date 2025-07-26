<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\GoodsReceiptUnitData as DataGoodsReceiptUnitData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class GoodsReceiptUnitData extends Data implements DataGoodsReceiptUnitData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('card_stock_id')]
    #[MapName('card_stock_id')]
    public mixed $card_stock_id = null;

    #[MapInputName('unit_id')]
    #[MapName('unit_id')]
    public mixed $unit_id = null;

    #[MapInputName('unit_name')]
    #[MapName('unit_name')]
    public ?string $unit_name = null;

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
        $new = static::new();
        self::createReceiveUnit($new,$data,$data->props);

        $data->unit_name = $data->props['prop_unit']['name'];
        return $data;
    }

    protected static function createReceiveUnit($new,$data,&$props){
        $item_stuff = $new->ReceiveUnitModel();
        if (isset($data->unit_id)) {
            $item_stuff = $item_stuff->withoutGlobalScopes()->findOrFail($data->unit_id);
        }else{
            if (isset($data->unit,$data->unit['name'])){
                $item_stuff = $item_stuff->firstOrCreate([
                    'name' => $data->unit['name'],
                    'flag' => 'UnitSale'
                ]);
            }
        }
        $props['prop_unit'] = [
            'id'    => $item_stuff->getKey(),
            'name'  => $item_stuff->name
        ];
    }
}