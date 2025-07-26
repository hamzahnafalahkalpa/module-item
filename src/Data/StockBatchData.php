<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\ModuleItem\Contracts\Data\StockBatchData as DataStockBatchData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class StockBatchData implements DataStockBatchData{
    #[MapInputName('batch_id')]
    #[MapName('batch_id')]
    public mixed $batch_id = null;

    #[MapInputName('stock')]
    #[MapName('stock')]
    public ?int $stock = 0;
}