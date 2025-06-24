<?php

namespace Hanafalah\ModuleItem\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleItem\{
    Supports\BaseModuleItem
};
use Hanafalah\ModuleItem\Contracts\Schemas\NetUnit as ContractsNetUnit;
use Hanafalah\ModuleItem\Contracts\Data\NetUnitData;

class NetUnit extends BaseModuleItem implements ContractsNetUnit
{
    protected string $__entity = 'NetUnit';
    public static $net_unit_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'net_unit',
            'tags'     => ['net_unit', 'net_unit-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreNetUnit(NetUnitData $net_unit_dto): Model{
        $add = [
            'name' => $net_unit_dto->name
        ];
        $guard  = ['id' => $net_unit_dto->id];
        $create = [$guard, $add];
        // if (isset($net_unit_dto->id)){
        //     $guard  = ['id' => $net_unit_dto->id];
        //     $create = [$guard, $add];
        // }else{
        //     $create = [$add];
        // }

        $net_unit = $this->usingEntity()->updateOrCreate(...$create);
        $this->fillingProps($net_unit,$net_unit_dto->props);
        $net_unit->save();
        return static::$net_unit_model = $net_unit;
    }
}