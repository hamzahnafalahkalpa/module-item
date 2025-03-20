<?php

namespace Gii\ModuleItem\Models;

use Zahzah\LaravelSupport\Models\BaseModel;

class ModelHasManufacture extends BaseModel
{
    protected $list  = ['id','model_type','model_id','manufacture_id'];

    public function manufacture(){return $this->belongsToModel('Manufacture');}
    public function model(){return $this->morphTo();}
}
