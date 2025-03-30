<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelSupport\Models\BaseModel;

class ModelHasManufacture extends BaseModel
{
    protected $list  = ['id', 'model_type', 'model_id', 'manufacture_id'];

    public function manufacture(){return $this->belongsToModel('Manufacture');}
    public function model(){return $this->morphTo();}
}
