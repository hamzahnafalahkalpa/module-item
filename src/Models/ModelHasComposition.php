<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;

class ModelHasComposition extends BaseModel
{
    use HasProps;
    protected $list  = ['id', 'model_type', 'model_id', 'composition_id', 'props'];

    public function composition()
    {
        return $this->belongsToModel('Composition');
    }
    public function model()
    {
        return $this->morphTo();
    }
}
