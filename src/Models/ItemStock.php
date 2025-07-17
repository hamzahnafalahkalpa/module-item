<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\ItemStock\{
    ShowItemStock, ViewItemStock
};
use Hanafalah\ModuleWarehouse\Models\Stock\Stock;

class ItemStock extends Stock
{
    protected $table = 'stocks';

    public function getViewResource(){
        return ViewItemStock::class;
    }

    public function getShowResource(){
        return ShowItemStock::class;
    }

    public function item(){return $this->morphTo('subject');}
}
