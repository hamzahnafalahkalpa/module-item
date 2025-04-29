<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\ItemData as DataItemData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ItemData extends Data implements DataItemData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('name')]
    #[MapName('name')]
    public mixed $name;

    #[MapInputName('barcode')]
    #[MapName('barcode')]
    public ?string $barcode = null;

    #[MapInputName('unit_id')]
    #[MapName('unit_id')]
    public mixed $unit_id = null;

    #[MapInputName('unit')]
    #[MapName('unit')]
    public ?array $unit = null;

    #[MapInputName('net_unit_id')]
    #[MapName('net_unit_id')]
    public mixed $net_unit_id = null;

    #[MapInputName('net_unit')]
    #[MapName('net_unit')]
    public ?array $net_unit = null;

    #[MapInputName('net_qty')]
    #[MapName('net_qty')]
    public mixed $net_qty = null;

    #[MapInputName('coa_id')]
    #[MapName('coa_id')]
    public mixed $coa_id = null;

    #[MapInputName('cogs')]
    #[MapName('cogs')]
    public ?int $cogs = 0;

    #[MapInputName('margin')]
    #[MapName('margin')]
    public ?int $margin = 20;

    #[MapInputName('min_stock')]
    #[MapName('min_stock')]
    public ?int $min_stock = 150;

    #[MapInputName('is_using_batch')]
    #[MapName('is_using_batch')]
    public ?bool $is_using_batch = false;

    #[MapInputName('tax')]
    #[MapName('tax')]
    public ?int $tax = 0;

    #[MapInputName('netto')]
    #[MapName('netto')]
    public mixed $netto = null;

    #[MapInputName('item_stock')]
    #[MapName('item_stock')]
    public ?ItemStockData $item_stock = null;

    #[MapInputName('compositions')]
    #[MapName('compositions')]
    #[DataCollectionOf(CompositionData::class)]
    public ?array $compositions = [];

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(ItemData $data): ItemData{
        $data->props['prop_unit'] = [
            'id'    => null,
            'name'  => null
        ];
        $data->props['prop_net_unit'] = [
            'id'    => null,
            'name'  => null
        ];
        if (isset($data->unit_id)) {
            $item_stuff = self::new()->ItemStuffModel()->findOrFail($data->unit_id);
            $data->props['prop_unit'] = [
                'id'    => $item_stuff->getKey(),
                'name'  => $item_stuff->name
            ];
        }else{
            if (isset($data->unit,$data->unit['name'])){
                $unit = self::new()->ItemStuffModel()->firstOrCreate([
                    'name' => $data->unit['name'],
                    'flag' => 'UNIT_SALES'
                ]);
                $data->props['prop_unit'] = [
                    'id'    => $unit->getKey(),
                    'name'  => $unit->name
                ];
            }
        }

        if (isset($item_dto->net_unit_id)) {
            $item_stuff = self::new()->ItemStuffModel()->findOrFail($item_dto->net_unit_id);
            $data->props['prop_net_unit'] = [
                'id'    => $item_stuff->getKey(),
                'name'  => $item_stuff->name
            ];
        }else{
            if (isset($data->net_unit,$data->net_unit['name'])){
                $unit = self::new()->ItemStuffModel()->firstOrCreate([
                    'name' => $data->net_unit['name'],
                    'flag' => 'NET_UNIT'
                ]);
                $data->props['prop_net_unit'] = [
                    'id'    => $unit->getKey(),
                    'name'  => $unit->name
                ];
            }
        }
        return $data;
    }
}