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
        'used_by_type',
        'user_by_id',
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

    protected static function booted(): void{
        parent::booted();
        static::creating(function ($query) {
            $query->inventory_code ??= static::hasEncoding('INVENTORY');
        });
    }

    public function viewUsingRelation(): array{
        return ['item.itemStock', 'reference'];
    }

    public function showUsingRelation(): array{
        return [
            'reference',
            'item' => function ($query) {
                $query->with([
                    'itemHasVariants',
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

    public function getViewResource(){return ViewInventory::class;}
    public function getShowResource(){return ShowInventory::class;}
    protected function isUsingService(): bool{
        $reference = $this->reference;
        $configs = config('module-service.is_using_services',[]);
        return in_array($reference->getMorphClass(), $configs) || (method_exists($reference, 'isUsingService') && $reference->isUsingService());
    }

    public function brand(){return $this->belongsToModel('Brand');}
    public function supplyCategory(){return $this->belongsToModel('SupplyCategory');}
    public function inventoryAsset(){return $this->hasOneModel('InventoryAsset');}
    public function inventoryAssets(){return $this->hasManyModel('InventoryAsset');}
    public function reference(){return $this->morphTo();}
    public function usedBy(){return $this->morphTo();}
}
