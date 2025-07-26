<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\StuffSupply\{
    ViewStuffSupply, ShowStuffSupply
};

class StuffSupply extends InventoryItem
{
    protected $table = 'inventory_items';

    public function getViewResource(){
        return ViewStuffSupply::class;
    }

    public function getShowResource(){
        return ShowStuffSupply::class;
    }
}
