<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\OfficeSupplyData as DataOfficeSupplyData;

class OfficeSupplyData extends InventoryItemData implements DataOfficeSupplyData
{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'OfficeSupply';
        parent::before($attributes);
    }
}