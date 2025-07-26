<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\BrandData as DataBrandData;

class BrandData extends ItemStuffData implements DataBrandData
{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'Brand';
        parent::before($attributes);
    }
}