<?php

namespace Gii\ModuleItem\Models;

use Gii\ModuleItem\Resources\Composition\ViewComposition;
use Zahzah\LaravelHasProps\Concerns\HasProps;
use Zahzah\LaravelSupport\Models\BaseModel;

class Composition extends BaseModel
{
    use HasProps;
    public $timestamps = false;
    protected $list  = ['id', 'name', 'unit_scale', 'unit_id', 'unit_name', 'props'];

    public function toViewApi(){
        return new ViewComposition($this);
    }

    public function toShowApi(){
        return new ViewComposition($this);
    }

    public function unit(){return $this->belongsToModel('ItemStuff','unit_id');}
    public function ModelHasComposition(){return $this->hasOneModel('ModelHasComposition');}
    public function ModelHasCompositions(){return $this->hasManyModel('ModelHasComposition');}
}
