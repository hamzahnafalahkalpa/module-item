<?php

namespace Gii\ModuleItem\Models;

use Gii\ModuleItem\Resources\ItemStuff\ViewItemStuff;
use Zahzah\LaravelHasProps\Concerns\HasProps;
use Zahzah\LaravelSupport\Models\BaseModel;

class ItemStuff extends BaseModel {
    use HasProps;

    public $timestamps = false;
    protected $list = ['id','parent_id','name','flag','props'];

    public function toViewApi(){
        return new ViewItemStuff($this);
    }

    public function toShowApi(){
        return new ViewItemStuff($this);
    }

    //OVERIDING DEFAULT CHILDS EIGER
    public function childs(){
        return $this->hasMany(get_class($this),static::getParentId())->with('childs');
      }
}
