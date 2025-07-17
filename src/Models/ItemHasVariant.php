<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\ModuleItem\Resources\ItemHasVariant\{
    ShowItemHasVariant, ViewItemHasVariant
};

class ItemHasVariant extends BaseModel{
    use HasUlids, HasProps, SoftDeletes;

    public $incrementing  = false;
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    protected $list = [
        'id', 'item_id', 'variant_name', 'variant_type', 'variant_id', 'props'
    ];

    public function getViewResource(){
        return ViewItemHasVariant::class;
    }

    public function getShowResource(){
        return ShowItemHasVariant::class;
    }

    public function item(){return $this->belongsToModel('Item');}
    public function variant(){return $this->morphTo('variant');}
}