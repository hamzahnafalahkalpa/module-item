<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\InventoryItemData as DataInventoryItemData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class InventoryItemData extends Data implements DataInventoryItemData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;
    
    #[MapInputName('name')]
    #[MapName('name')]
    public string $name;
    
    #[MapInputName('flag')]
    #[MapName('flag')]
    public string $flag;

    #[MapInputName('label')]
    #[MapName('label')]
    public ?string $label = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function before(array &$attributes){
    }
}