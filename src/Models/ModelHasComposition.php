<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class ModelHasComposition extends BaseModel
{
    use HasUlids, HasProps;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $list  = ['id', 'model_type', 'model_id', 'composition_id', 'props'];

    public function composition(){return $this->belongsToModel('Composition');}
    public function model(){return $this->morphTo();}
}
