<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\NetUnitData as DataNetUnitData;

class NetUnitData extends ItemStuffData implements DataNetUnitData
{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'NetUnit';
        parent::before($attributes);
    }
}