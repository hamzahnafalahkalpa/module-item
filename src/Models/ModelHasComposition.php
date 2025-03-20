<?php

namespace Gii\ModuleItem\Models;

use Zahzah\LaravelHasProps\Concerns\HasProps;
use Zahzah\LaravelSupport\Models\BaseModel;

class ModelHasComposition extends BaseModel
{
    use HasProps;
    protected $list  = ['id','model_type','model_id','composition_id','props'];

    public function composition(){return $this->belongsToModel('Composition');}
    public function model(){return $this->morphTo();}
}
