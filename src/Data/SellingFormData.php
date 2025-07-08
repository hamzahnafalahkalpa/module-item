<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\SellingFormData as DataSellingFormData;

class SellingFormData extends ItemStuffData implements DataSellingFormData
{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'SellingForm';
        parent::before($attributes);
    }
}