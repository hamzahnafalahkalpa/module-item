<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\VariantData as DataVariantData;

class VariantData extends ItemStuffData implements DataVariantData{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'Variant';
        parent::before($attributes);
    }
}