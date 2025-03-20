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

    public function toViewApi()
    {
        return new ViewItemStuff($this);
    }

    public function toShowApi()
    {
        return new ViewItemStuff($this);
    }

    //OVERIDING DEFAULT CHILDS EIGER
    public function childs()
    {
        return $this->hasMany(get_class($this), static::getParentId())->with('childs');
    }
}
