<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends BaseModel{
    use HasUlids, HasProps, HasProps, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
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