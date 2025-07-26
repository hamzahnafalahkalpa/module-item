<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\SupplyCategory\{
    ViewSupplyCategory,
    ShowSupplyCategory
};

class SupplyCategory extends ItemStuff
{
    protected $table = 'unicodes';

    public function getViewResource(){
        return ViewSupplyCategory::class;
    }

    public function getShowResource(){
        return ShowSupplyCategory::class;
    }
}
