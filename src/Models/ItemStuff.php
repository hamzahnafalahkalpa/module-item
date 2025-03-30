<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\ItemStuff\ViewItemStuff;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;

class ItemStuff extends BaseModel
{
    use HasProps;

    public $timestamps = false;
    protected $list = ['id', 'parent_id', 'name', 'flag', 'props'];

    public function getViewResource(){
        return ViewItemStuff::class;
    }

    public function getShowResource(){
        return ViewItemStuff::class;
    }

    //OVERIDING DEFAULT CHILDS EIGER
    public function childs(){
        return $this->hasMany(get_class($this), static::getParentId())->with('childs');
    }
}
