<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\Brand as ContractsBrand;

class Brand extends ItemStuff implements ContractsBrand
{
    protected string $__entity = 'Brand';
    public static $brand_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'brand',
            'tags'     => ['brand', 'brand-index'],
            'duration' => 24 * 60
        ]
    ];
}