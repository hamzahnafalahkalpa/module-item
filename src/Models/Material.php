<?php

namespace Gii\ModuleItem\Models;

use Gii\ModuleItem\Resources\Material\ShowMaterial;
use Gii\ModuleItem\Resources\Material\ViewMaterial;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zahzah\LaravelHasProps\Concerns\HasProps;
use Zahzah\LaravelSupport\Models\BaseModel;

class Material extends BaseModel {
    use SoftDeletes, HasProps;

    protected $list = ['id','name','props'];

    public function toShowApi()
    {
        return new ShowMaterial($this);
    }

    public function toViewApi(){
        return new ViewMaterial($this);
    }

    public function billOfMaterial(){return $this->hasOneModel('BillOfMaterial');}
    public function billOfMaterials(){return $this->hasManyModel('BillOfMaterial');}
    public function items(){
        return $this->belongsToManyModel(
            'Item','BillOfMaterial',
            'material_id','item_id'
        );
    }
}
