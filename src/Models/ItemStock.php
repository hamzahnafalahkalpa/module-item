<?php

namespace Gii\ModuleItem\Models;

use Gii\ModuleItem\Resources\ItemStock\ShowItemStock;
use Gii\ModuleItem\Resources\ItemStock\ViewItemStock;
use Zahzah\ModuleWarehouse\Models\Stock\Stock;

class ItemStock extends Stock {
    protected $table = 'stocks';

    public function toViewApi(){
        return new ViewItemStock($this);
    }

    public function toShowApi(){
        return new ShowItemStock($this);
    }

    public function item(){return $this->morphTo('subject');}
}
