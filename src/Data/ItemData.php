<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\ItemData as DataItemData;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Illuminate\Support\Str;

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

    #[MapInputName('reference')]
    #[MapName('reference')]
    public array|object|null $reference = null;
    
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
    public ?ItemPropsData $props = null;

    public static function before(array &$attributes){
        if (isset($attributes['reference_model'])){
            $model = $attributes['reference_model'];
            $attributes['reference_type'] = $model->getMorphClass();
            $attributes['reference_id']   = $model->getKey();
        }
        if (isset($attributes['reference'])){
            $attributes['reference']['name'] ??= $attributes['name'];
            $attributes['name'] ??= $attributes['reference']['name'];
        }
        if (!isset($attributes['id']) && !isset($attributes['reference_type'])){
            $config_keys = array_keys(config('module-item.item_reference_types'));
            $keys        = array_intersect(array_keys(request()->all()),$config_keys);
            $key         = array_shift($keys);
            $attributes['reference_type'] ??= request()->reference_type ?? $key;
            $attributes['reference_type'] = Str::studly($attributes['reference_type']);
        }
        
        if (isset($attributes['item_has_variants'])){
            $attributes['prop_item_has_variants'] = $attributes['item_has_variants'];
            unset($attributes['item_has_variants']);
        }
    }

    public static function after(ItemData $data): ItemData{
        $new = static::new();
        $props = &$data->props->props;

        if (isset($data->reference)){
            $reference = &$data->reference;
            $reference = self::transformToData($data->reference_type, $reference);
        }

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
                $data->net_unit_id = $item_stuff->getKey();
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
                    'flag' => 'SellingForm'
                ]);
                $data->unit_id = $item_stuff->getKey();
            }
        }
        $props['prop_unit'] = [
            'id'    => $item_stuff->getKey(),
            'name'  => $item_stuff->name
        ];
    }

    private static function transformToData(string $entity,array $attributes){
        $new = static::new();
        return $new->requestDTO(config('app.contracts.'.$entity.'Data'),$attributes);
    }
}