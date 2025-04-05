<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\ItemStockData as DataItemStockData;
use Hanafalah\ModuleWarehouse\Data\StockData;
// use Spatie\LaravelData\Attributes\DataCollectionOf;
// use Spatie\LaravelData\Attributes\MapInputName;
// use Spatie\LaravelData\Attributes\MapName;

class ItemStockData extends StockData implements DataItemStockData{
    // public function __construct(
    //     #[MapInputName('id')]
    //     #[MapName('id')]
    //     public mixed $id = null,

    //     #[MapInputName('stock')]
    //     #[MapName('stock')]
    //     public mixed $stock = null,

    //     #[MapInputName('stock_batches')]
    //     #[MapName('stock_batches')]
    //     #[DataCollectionOf(StockBatchData::class)]
    //     public ?array $stock_batches = []
    // ){}
}