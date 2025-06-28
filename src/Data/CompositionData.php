<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\CompositionData as DataCompositionData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Contracts\BaseData;

class CompositionData extends Data implements DataCompositionData, BaseData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id;

    #[MapInputName('name')]
    #[MapName('name')]
    public string $name;

    #[MapInputName('unit_scale')]
    #[MapName('unit_scale')]
    public string $unit_scale;

    #[MapInputName('unit_id')]
    #[MapName('unit_id')]
    public mixed $unit_id;

    #[MapInputName('unit_name')]
    #[MapName('unit_name')]
    public ?string $unit_name = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public mixed $props;

    public static function after(self $data): self{
        $new = self::new();
        $props = &$data->props;

        $item_stuff = $new->ItemStuffModel();
        $item_stuff = (isset($data->unit_id)) ? $item_stuff->withoutGlobalScopes()->findOrFail($data->unit_id) : $item_stuff;
        $props['prop_unit'] = $item_stuff->toViewApi()->only(['id','name']);

        $data->unit_name = $item_stuff->name;
        return $data;
    }
}