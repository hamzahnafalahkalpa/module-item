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
    public mixed $transaction_id;

    #[MapInputName('item_id')]
    #[MapName('item_id')]
    public mixed $item_id;

    #[MapInputName('total_qty')]
    #[MapName('total_qty')]
    public ?float $total_qty = 0;

    #[MapInputName('receive_qty')]
    #[MapName('receive_qty')]
    public ?float $receive_qty = 0;

    #[MapInputName('request_qty')]
    #[MapName('request_qty')]
    public ?float $request_qty = 0;

    #[MapInputName('total_cogs')]
    #[MapName('total_cogs')]
    public ?int $total_cogs = 0;

    #[MapInputName('total_tax')]
    #[MapName('total_tax')]
    public ?int $total_tax = 0;

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

    public static function before(array &$attributes){
        $new = self::new();
        $item = $new->ItemModel()->findOrFail($attributes['item_id']);
        $attributes['prop_item'] = $item->toViewApi()->resolve();

        $attributes['stock_movements'] ??= [];
        if (isset($attributes['stock_movement'])) {
            $attributes['stock_movements'][] = $attributes['stock_movement'];
            unset($attributes['stock_movement']);
        }

        foreach ($attributes['stock_movements'] as &$stock_movement) {
            $stock_movement['qty_unit_id'] ??= $item->unit_id;
        }
    }

    public static function after(CardStockData $data): CardStockData{
        $new = self::new();

        $props = &$data->props->props;

        if (isset($data->reference_type)){
            $reference = $new->{$data->reference_type.'Model'}();
            if (isset($data->reference_id)) $reference = $reference->findOrFail($data->reference_id);
            $props['prop_reference'] = $reference->toViewApi()->resolve();
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