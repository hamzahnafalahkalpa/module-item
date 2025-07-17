<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Data\UnicodeData;
use Hanafalah\ModuleItem\Contracts\Data\CompositionUnitData as DataCompositionUnitData;

class CompositionUnitData extends UnicodeData implements DataCompositionUnitData
{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'CompositionUnit';
        parent::before($attributes);
    }
}