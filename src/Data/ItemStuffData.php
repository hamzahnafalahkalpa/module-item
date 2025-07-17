<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Data\UnicodeData;
use Hanafalah\ModuleItem\Contracts\Data\ItemStuffData as DataItemStuffData;

class ItemStuffData extends UnicodeData implements DataItemStuffData{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'ItemStuff';
        parent::before($attributes);
    }
}