<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends BaseModel{
    use HasProps, HasProps, SoftDeletes;

    protected $list = [
        'id', 'name', 'flag', 'props'
    ];

    protected static function booted(): void{
        static::deleted(function($query){
            $query->itemHasVariants()->delete();
        });
    }

    public function itemHasVariant(){return $this->morphOneModel('ItemHasVariant','variant');}
    public function itemHasVariants(){return $this->morphManyModel('ItemHasVariant','variant');}
}