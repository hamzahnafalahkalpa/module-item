<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\OfficeSupply as ContractsOfficeSupply;

class OfficeSupply extends InventoryItem implements ContractsOfficeSupply
{
    protected string $__entity = 'OfficeSupply';
    public $office_supply_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'office_supply',
            'tags'     => ['office_supply', 'office_supply-index'],
            'duration' => 24 * 60
        ]
    ];
}