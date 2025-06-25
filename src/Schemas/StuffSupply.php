<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\StuffSupply as ContractsStuffSupply;

class StuffSupply extends InventoryItem implements ContractsStuffSupply
{
    protected string $__entity = 'StuffSupply';
    public static $stuff_supply_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'stuff_supply',
            'tags'     => ['stuff_supply', 'stuff_supply-index'],
            'duration' => 24 * 60
        ]
    ];
}