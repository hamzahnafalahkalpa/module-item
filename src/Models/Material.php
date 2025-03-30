<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\Material\ShowMaterial;
use Hanafalah\ModuleItem\Resources\Material\ViewMaterial;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;

class Material extends BaseModel
{
    use SoftDeletes, HasProps;

    protected $list = ['id', 'name', 'props'];

    public function getShowResource(){return ShowMaterial::class;}

    public function getViewResource(){return ViewMaterial::class;}

    public function billOfMaterial(){return $this->hasOneModel('BillOfMaterial');}
    public function billOfMaterials(){return $this->hasManyModel('BillOfMaterial');}
    public function items(){
        return $this->belongsToManyModel(
            'Item',
            'BillOfMaterial',
            'material_id',
            'item_id'
        );
    }
}
