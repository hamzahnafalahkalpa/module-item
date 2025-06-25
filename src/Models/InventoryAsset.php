<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\ModuleItem\Resources\InventoryAsset\{
    ViewInventoryAsset,
    ShowInventoryAsset
};
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class InventoryAsset extends BaseModel
{
    const STATUS_ACTIVE         = 'ACTIVE';
    const STATUS_MAINTENANCE    = 'MAINTENANCE';
    const STATUS_BROKEN         = 'BROKEN';
    const STATUS_DECOMMISIONED  = 'DECOMMISIONED';
    const CONDITION_EXCELLENT   = 'EXCELLENT';
    const CONDITION_GOOD        = 'GOOD';
    const CONDITION_BAD_LIGHT   = 'BAD_LIGHT';
    const CONDITION_BAD_HEAVY   = 'BAD_HEAVY';
    const USAGE_IN_USE          = 'IN_USE';
    const USAGE_STANDBY         = 'STANDBY';
    const USAGE_AVAILABLE       = 'AVAILABLE';
    const USAGE_LOANED          = 'LOANED';

    use HasUlids, HasProps, SoftDeletes;
    
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    public $list = [
        'id',
        'inventory_id',
        'item_id',
        'serial_number',
        'warehouse_type',
        'warehouse_id',
        'status_id',
        'condition',
        'condition_label_id',
        'usage_status_id',
        'purchased_at',
        'warranty_expiry_at',
        'last_maintenance_at',
        'next_maintenance_due',
        'is_calibration_required',
        'props',
    ];

    protected $casts = [
        'name' => 'string'
    ];

    public function viewUsingRelation(): array{
        return [];
    }

    public function showUsingRelation(): array{
        return [];
    }

    public function getViewResource(){
        return ViewInventoryAsset::class;
    }

    public function getShowResource(){
        return ShowInventoryAsset::class;
    }

    public function inventory(){return $this->belongsToModel('Inventory');}
    public function item(){return $this->belongsToModel('Item');}
}
