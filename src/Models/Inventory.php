<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\ModuleItem\Concerns\HasItemWithInventoryAsset;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\ModuleItem\Resources\Inventory\{
    ViewInventory, ShowInventory
};
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Inventory extends BaseModel
{
    use HasUlids, HasProps, SoftDeletes, HasItemWithInventoryAsset;
    
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    public $list = [
        'id',
        'inventory_code',
        'name',
        'reference_type',
        'reference_id',
        'brand_id',
        'supply_category_id',
        'model_name',
        'props',
    ];

    public $show = [
        'description'
    ];

    protected $casts = [
        'inventory_code'       => 'string',
        'name'                 => 'string',
        'brand_name'           => 'string',
        'supply_category_name' => 'string',
        'model_name'           => 'string'
    ];

    public function getPropsQuery(): array{
        return [
            'brand_name'           => 'props->prop_brand->name',
            'supply_category_name' => 'props->prop_supply_category->name'
        ];
    }

    public function viewUsingRelation(): array{
        return ['item.itemStock', 'reference'];
    }

    public function showUsingRelation(): array{
        return [
            'reference',
            'item' => function ($query) {
                $query->with([
                    'itemStock' => function ($query) {
                        $query->with([
                            'childs.stockBatches.batch',
                            'stockBatches.batch'
                        ]);
                    }
                ]);
            }
        ];
    }

    public function getViewResource(){
        return ViewInventory::class;
    }

    public function getShowResource(){
        return ShowInventory::class;
    }

    public function brand(){return $this->belongsToModel('Brand');}
    public function supplyCategory(){return $this->belongsToModel('SupplyCategory');}
    public function inventoryAsset(){return $this->hasOneModel('InventoryAsset');}
    public function inventoryAssets(){return $this->hasManyModel('InventoryAsset');}
    public function reference(){return $this->morphTo();}
}
