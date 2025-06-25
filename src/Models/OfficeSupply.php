<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\OfficeSupply\{
    ViewOfficeSupply, ShowOfficeSupply
};

class OfficeSupply extends InventoryItem
{
    protected $table = 'inventory_items';

    public function getViewResource(){
        return ViewOfficeSupply::class;
    }

    public function getShowResource(){
        return ShowOfficeSupply::class;
    }
}
