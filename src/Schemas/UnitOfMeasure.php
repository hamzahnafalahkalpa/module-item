<?php

namespace Hanafalah\ModuleItem\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleItem\{
    Supports\BaseModuleItem
};
use Hanafalah\ModuleItem\Contracts\Schemas\UnitOfMeasure as ContractsUnitOfMeasure;
use Hanafalah\ModuleItem\Contracts\Data\UnitOfMeasureData;

class UnitOfMeasure extends BaseModuleItem implements ContractsUnitOfMeasure
{
    protected string $__entity = 'UnitOfMeasure';
    public $unit_of_measure_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'unit_of_measure',
            'tags'     => ['unit_of_measure', 'unit_of_measure-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreUnitOfMeasure(UnitOfMeasureData $unit_of_measure_dto): Model{
        $add = [
            'name' => $unit_of_measure_dto->name
        ];
        $guard  = ['id' => $unit_of_measure_dto->id];
        $create = [$guard, $add];
        // if (isset($unit_of_measure_dto->id)){
        //     $guard  = ['id' => $unit_of_measure_dto->id];
        //     $create = [$guard, $add];
        // }else{
        //     $create = [$add];
        // }

        $unit_of_measure = $this->usingEntity()->updateOrCreate(...$create);
        $this->fillingProps($unit_of_measure,$unit_of_measure_dto->props);
        $unit_of_measure->save();
        return $this->unit_of_measure_model = $unit_of_measure;
    }
}