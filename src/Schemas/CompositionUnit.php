<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\CompositionUnit as ContractsCompositionUnit;

class CompositionUnit extends ItemStuff implements ContractsCompositionUnit
{
    protected string $__entity = 'CompositionUnit';
    public static $composition_unit_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'composition_unit',
            'tags'     => ['composition_unit', 'composition_unit-index'],
            'duration' => 24 * 60
        ]
    ];
}