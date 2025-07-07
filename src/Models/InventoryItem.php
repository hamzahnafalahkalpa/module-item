<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\ModuleItem\Concerns\HasInventory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\ModuleItem\Resources\InventoryItem\{
    ViewInventoryItem, ShowInventoryItem
};
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class InventoryItem extends BaseModel
{
    use HasUlids, HasProps, SoftDeletes, HasInventory;
    
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    public $list = [
        'id', 
        'name', 
        'flag',
        'label',
        'props'
    ];

    protected $casts = [
        'name'                 => 'string',
        'code'                 => 'string',
        'label'                => 'string',
        'supply_category_name' => 'string',
        'brand_name'           => 'string'
    ];

    public function getPropsQuery(): array{
        return [
            'supply_category_name' => 'props->prop_supply_category->name',
            'brand_name'           => 'props->prop_brand->name'
        ];
    }

    protected static function booted(): void{
        parent::booted();
        static::addGlobalScope('flag',function($query){
            $query->where('flag',(new static)->getMorphClass());
        });
        static::creating(function ($query) {
            $query->flag ??= (new static)->getMorphClass();
        });
    }

    public function viewUsingRelation(): array{
        return [];
    }

    public function showUsingRelation(): array{
        return [];
    }

    public function getViewResource(){return ViewInventoryItem::class;}
    public function getShowResource(){return ShowInventoryItem::class;}

    public function supplyCategory(){return $this->belongsToModel('SupplyCategory');}
    public function brand(){return $this->belongsToModel('Brand');}
}
