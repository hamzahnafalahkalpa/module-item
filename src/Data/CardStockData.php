<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\CardStockData as DataCardStockData;
use Hanafalah\ModuleWarehouse\Contracts\Data\StockMovementData;
use Hanafalah\ModuleWarehouse\Data\StockMovementData as DataStockMovementData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class CardStockData extends Data implements DataCardStockData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

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

    #[MapInputName('stock_movement')]
    #[MapName('stock_movement')]
    public ?StockMovementData $stock_movement = null;

    #[MapInputName('stock_movements')]
    #[MapName('stock_movements')]
    #[DataCollectionOf(DataStockMovementData::class)]
    public ?array $stock_movements = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(CardStockData $data): CardStockData{
        $data->props['prop_item'] = [
            'id'    => $data->item_id ?? null,
            'name'  => null
        ];
        if (isset($data->props['prop_item']['id']) && !isset($data->props['prop_item']['name'])){
            $item = self::new()->ItemModel()->findOrFail($data->props['prop_item']['id']);
            $data->props['prop_item']['name'] = $item->name;
        }
        
        $data->props['prop_reference'] = [
            'id'    => $data->reference_id ?? null,
            'name'  => null
        ];
        if (isset($data->props['prop_reference']['id']) && !isset($data->props['prop_reference']['name'])){
            $reference = self::new()->{$data->reference_type.'Model'}()->findOrFail($data->props['prop_reference']['id']);
            $data->props['prop_reference']['name'] = $reference->name ?? null;
        }

        if (isset($data->props['cogs'],$data->props['qty'])){
            $data->props['total_cogs'] = $data->props['cogs'] * $data->props['qty'];
        }
        return $data;
    }
}