<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Concerns\Support\HasRequestData;
use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\InventoryData as DataInventoryData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Illuminate\Support\Str;

class InventoryData extends Data implements DataInventoryData
{
    use HasRequestData;
    
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('name')]
    #[MapName('name')]
    public ?string $name = null;

    #[MapInputName('inventory_code')]
    #[MapName('inventory_code')]
    public ?string $inventory_code = null;

    #[MapInputName('reference_type')]
    #[MapName('reference_type')]
    public ?string $reference_type = null;

    #[MapInputName('reference_id')]
    #[MapName('reference_id')]
    public mixed $reference_id = null;

    #[MapInputName('reference')]
    #[MapName('reference')]
    public object|array $reference;

    #[MapInputName('reference_model')]
    #[MapName('reference_model')]
    public ?object $reference_model = null;

    #[MapInputName('brand_id')]
    #[MapName('brand_id')]
    public mixed $brand_id = null;

    #[MapInputName('supply_category_id')]
    #[MapName('supply_category_id')]
    public mixed $supply_category_id = null;

    #[MapInputName('item')]
    #[MapName('item')]
    public ItemData $item;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = null;

    public static function before(array &$attributes){
        $new = static::new();
        $attributes['reference']['name'] = $attributes['name'];
        $attributes['item']['name']      = $attributes['name'];

        if (isset($attributes['id'])){
            $medical_item_model   = $new->InventoryModel()->with('reference')->findOrFail($attributes['id']);
            $attributes['reference_id']   = $reference['id'] = $medical_item_model->reference_id;
            $attributes['reference_type'] = $medical_item_model->reference_type;
        }else{
            if (isset($attributes['reference_type'])){
                $attributes[Str::snake($attributes['reference_type'])] = [
                    'id' => null
                ];
            }else{
                $config_keys = array_keys(config('module-item.inventory_types'));
                $keys        = array_intersect(array_keys(request()->all()),$config_keys);
                $key         = array_shift($keys);
                $attributes['reference_type'] ??= request()->reference_type ?? $key;
                $attributes['reference_type'] = Str::studly($attributes['reference_type']);
            }
        }
    }

    public static function after(self $data): self{
        $new = static::new();
        $reference  = &$data->reference;
        $reference  = self::transformToData($data->reference_type, $reference);
        $data->name = $reference->name;
        
        $brand = $new->BrandModel();
        $brand = (isset($data->brand_id)) ? $brand->findOrFail($data->brand_id) : $brand;
        $data->props['prop_brand'] = $brand->toViewApi()->only(['id','name']);

        $supply_category = $new->SupplyCategoryModel();
        $supply_category = (isset($data->supply_category_id)) ? $supply_category->findOrFail($data->supply_category_id) : $supply_category;
        $data->props['prop_supply_category'] = $supply_category->toViewApi()->only(['id','name']);
        return $data;
    }

    private static function transformToData(string $entity,array $attributes){
        $new = static::new();
        return $new->requestDTO(config('app.contracts.'.$entity.'Data'),$attributes);
    }
}