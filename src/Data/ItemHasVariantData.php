<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\ItemHasVariantData as DataItemHasVariantData;
use Hanafalah\ModuleItem\Contracts\Data\VariantData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ItemHasVariantData extends Data implements DataItemHasVariantData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('item_id')]
    #[MapName('item_id')]
    public mixed $item_id = null;

    #[MapInputName('variant_name')]
    #[MapName('variant_name')]
    public ?string $variant_name = null;

    #[MapInputName('variant_label')]
    #[MapName('variant_label')]
    public ?string $variant_label = null;

    #[MapInputName('variant_type')]
    #[MapName('variant_type')]
    public ?string $variant_type = null;

    #[MapInputName('variant_id')]
    #[MapName('variant_id')]
    public mixed $variant_id = null;

    #[MapInputName('variant')]
    #[MapName('variant')]
    public ?VariantData $variant = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = null;
}