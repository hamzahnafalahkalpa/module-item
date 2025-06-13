<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\CardStockData as DataCardStockData;
use Hanafalah\ModuleItem\Contracts\Data\CardStockPropsData;
use Hanafalah\ModuleItem\Contracts\Data\ItemData;
use Hanafalah\ModuleWarehouse\Contracts\Data\StockMovementData;
use Hanafalah\ModuleWarehouse\Data\StockMovementData as DataStockMovementData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class CardStockData extends Data implements DataCardStockData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('parent_id')]
    #[MapName('parent_id')]
    public mixed $parent_id = null;

    #[MapInputName('reference_type')]
    #[MapName('reference_type')]
    public ?string $reference_type = null;

    #[MapInputName('reference_id')]
    #[MapName('reference_id')]
    public mixed $reference_id = null;

    #[MapInputName('transaction_id')]
    #[MapName('transaction_id')]
    public mixed $transaction_id = null;

    #[MapInputName('item_id')]
    #[MapName('item_id')]
    public mixed $item_id;

    #[MapInputName('item')]
    #[MapName('item')]
    public ?ItemData $item;

    #[MapInputName('stock_movement')]
    #[MapName('stock_movement')]
    public ?StockMovementData $stock_movement = null;

    #[MapInputName('stock_movements')]
    #[MapName('stock_movements')]
    #[DataCollectionOf(DataStockMovementData::class)]
    public ?array $stock_movements = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?CardStockPropsData $props = null;

    public static function after(CardStockData $data): CardStockData{
        $props = &$data->props->props;
        $props['prop_item'] = [
            'id'    => $data->item_id ?? null,
            'name'  => null
        ];
        if (isset($props['prop_item']['id']) && !isset($props['prop_item']['name'])){
            $item = self::new()->ItemModel()->findOrFail($props['prop_item']['id']);
            $props['prop_item']['name'] = $item->name;
        }
        
        $props['prop_reference'] = [
            'id'    => $data->reference_id ?? null,
            'name'  => null
        ];
        if (isset($props['prop_reference']['id']) && !isset($props['prop_reference']['name'])){
            $reference = self::new()->{$data->reference_type.'Model'}()->findOrFail($props['prop_reference']['id']);
            $props['prop_reference']['name'] = $reference->name ?? null;
        }

        if (isset($props['tax'],$props['qty'])){
            $props['total_tax'] ??= [
                'total' => 0,
                'ppn' => 0
            ];
            $props['total_tax'] ??= 0;
            if (is_array($props['tax'])){
                foreach ($props['tax'] as $key => $tax) {
                    $tax_sum = $tax * $props['qty'];
                    $props['total_tax']['total'] += $tax_sum;
                    $props['total_tax'][$key]   ??= 0;
                    $props['total_tax'][$key]    += $tax_sum;
                }
            }else{
                $props['total_tax'] += $props['tax'] * $props['qty'];
            }
        }
        return $data;
    }
}