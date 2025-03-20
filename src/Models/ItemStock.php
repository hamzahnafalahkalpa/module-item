<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\ItemStock\ShowItemStock;
use Hanafalah\ModuleItem\Resources\ItemStock\ViewItemStock;
use Hanafalah\ModuleWarehouse\Models\Stock\Stock;

class ItemStock extends Stock
{
    protected $table = 'stocks';

    public function toViewApi()
    {
        return new ViewItemStock($this);
    }

    public function toShowApi()
    {
        return new ShowItemStock($this);
    }

    public function item()
    {
        return $this->morphTo('subject');
    }
}
