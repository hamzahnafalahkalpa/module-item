<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\ItemPropsData as DataItemPropsData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ItemPropsData extends Data implements DataItemPropsData{
    #[MapInputName('prop_item_has_variants')]
    #[MapName('prop_item_has_variants')]
    #[DataCollectionOf(ItemHasVariantData::class)]
    public ?array $prop_item_has_variants = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = null;
}