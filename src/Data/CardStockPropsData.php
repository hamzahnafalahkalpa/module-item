<?php

namespace Hanafalah\ModuleItem\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleItem\Contracts\Data\CardStockPropsData as DataCardStockPropsData;
use Hanafalah\ModuleTax\Contracts\Data\TaxData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class CardStockPropsData extends Data implements DataCardStockPropsData{
    #[MapInputName('receive_qty')]
    #[MapName('receive_qty')]
    public ?float $receive_qty = null;

    #[MapInputName('tax')]
    #[MapName('tax')]
    public ?TaxData $tax = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];
}