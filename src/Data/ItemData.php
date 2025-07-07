<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\ItemData as DataItemData;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ItemData extends Data implements DataItemData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('name')]
    #[MapName('name')]
    public mixed $name;

    #[MapInputName('reference_type')]
    #[MapName('reference_type')]
    public ?string $reference_type = null;

    #[MapInputName('reference_id')]
    #[MapName('reference_id')]
    public mixed $reference_id = null;

    #[MapInputName('reference_model')]
    #[MapName('reference_model')]
    public ?Model $reference_model = null;

    #[MapInputName('barcode')]
    #[MapName('barcode')]
    public ?string $barcode = null;

    #[MapInputName('unit_id')]
    #[MapName('unit_id')]
    public mixed $unit_id = null;

    #[MapInputName('unit')]
    #[MapName('unit')]
    public ?array $unit = null;

    #[MapInputName('net_unit_id')]
    #[MapName('net_unit_id')]
    public mixed $net_unit_id = null;

    #[MapInputName('net_unit')]
    #[MapName('net_unit')]
    public ?array $net_unit = null;

    #[MapInputName('net_qty')]
    #[MapName('net_qty')]
    public mixed $net_qty = null;

    #[MapInputName('coa_id')]
    #[MapName('coa_id')]
    public mixed $coa_id = null;

    #[MapInputName('cogs')]
    #[MapName('cogs')]
    public ?int $cogs = 0;

    #[MapInputName('selling_price')]
    #[MapName('selling_price')]
    public ?int $selling_price = 0;

    #[MapInputName('margin')]
    #[MapName('margin')]
    public ?int $margin = 20;

    #[MapInputName('min_stock')]
    #[MapName('min_stock')]
    public ?int $min_stock = 150;

    #[MapInputName('is_using_batch')]
    #[MapName('is_using_batch')]
    public ?bool $is_using_batch = false;

    #[MapInputName('tax')]
    #[MapName('tax')]
    public ?int $tax = 0;

    #[MapInputName('netto')]
    #[MapName('netto')]
    public mixed $netto = null;

    #[MapInputName('item_stock')]
    #[MapName('item_stock')]
    public ?ItemStockData $item_stock = null;

    #[MapInputName('compositions')]
    #[MapName('compositions')]
    #[DataCollectionOf(CompositionData::class)]
    public ?array $compositions = [];

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function before(array &$attributes){
        if (isset($attributes['reference_model'])){
            $model = $attributes['reference_model'];
            $attributes['reference_type'] = $model->getMorphClass();
            $attributes['reference_id'] = $model->getKey();
        }
    }

    public static function after(ItemData $data): ItemData{
        $new = static::new();
        $props = &$data->props;

        $data->props['prop_unit'] = [
            'id'    => null,
            'name'  => null
        ];
        
        self::createUnitSale($new,$data,$props);
        self::createNetUnit($new,$data,$props);

        $coa = $new->CoaModel();
        $coa = (isset($data->coa_id)) ? $coa->findOrFail($data->coa_id) : $coa;
        $props['prop_coa'] = $coa->toViewApi()->resolve();

        $data->selling_price ??= $data->cogs + $data->cogs * $data->margin/100;
        return $data;
    }

    protected static function createNetUnit($new,$data,&$props){
        $item_stuff = $new->NetUnitModel();
        if (isset($data->net_unit_id)) {
            $item_stuff = $item_stuff->withoutGlobalScopes()->findOrFail($data->net_unit_id);
        }else{
            if (isset($data->net_unit,$data->net_unit['name'])){
                $item_stuff = $item_stuff->firstOrCreate([
                    'name' => $data->net_unit['name'],
                    'flag' => 'NetUnit'
                ]);
            }
        }
        $props['prop_net_unit'] = [
            'id'    => $item_stuff->getKey(),
            'name'  => $item_stuff->name
        ];
    }

    protected static function createUnitSale($new,$data,&$props){
        $item_stuff = $new->SellingFormModel();
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