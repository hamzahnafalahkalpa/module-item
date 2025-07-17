<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\Composition\ViewComposition;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Composition extends BaseModel
{
    use HasUlids, HasProps;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $list  = [
        'id', 'name', 'unit_scale', 
        'unit_id', 'unit_name', 'props'
    ];

    public function getViewResource(){
        return ViewComposition::class;
    }

    public function getShowResource(){
        return ViewComposition::class;
    }

    public function unit(){return $this->belongsToModel('ItemStuff', 'unit_id');}
    public function ModelHasComposition(){return $this->hasOneModel('ModelHasComposition');}
    public function ModelHasCompositions(){return $this->hasManyModel('ModelHasComposition');}
}
