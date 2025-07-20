<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\NetUnitData;
use Hanafalah\ModuleItem\Contracts\Schemas\NetUnit as ContractsNetUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NetUnit extends ItemStuff implements ContractsNetUnit
{
    protected string $__entity = 'NetUnit';
    public $net_unit_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'net_unit',
            'tags'     => ['net_unit', 'net_unit-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreNetUnit(NetUnitData $net_unit_dto): Model{
        $net_unit_model = $this->prepareStoreUnicode($net_unit_dto);
        return $this->net_unit_model = $net_unit_model;
    }

    public function netUnit(mixed $conditionals = null): Builder{
        return $this->unicode($conditionals);
    }
}