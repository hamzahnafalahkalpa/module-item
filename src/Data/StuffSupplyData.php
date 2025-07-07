<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\StuffSupplyData as DataStuffSupplyData;

class StuffSupplyData extends InventoryItemData implements DataStuffSupplyData
{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'StuffSupply';
        parent::before($attributes);
    }
}