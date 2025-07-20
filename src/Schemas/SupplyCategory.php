<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\SupplyCategory as ContractsSupplyCategory;

class SupplyCategory extends ItemStuff implements ContractsSupplyCategory
{
    protected string $__entity = 'SupplyCategory';
    public $supply_category_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'supply_category',
            'tags'     => ['supply_category', 'supply_category-index'],
            'duration' => 24 * 60
        ]
    ];
}