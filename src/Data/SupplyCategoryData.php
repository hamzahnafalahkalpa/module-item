<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\SupplyCategoryData as DataSupplyCategoryData;

class SupplyCategoryData extends ItemStuffData implements DataSupplyCategoryData
{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'SupplyCategory';
        parent::before($attributes);
    }
}