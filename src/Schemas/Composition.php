<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\{
    Composition as ContractsComposition
};
use Illuminate\Database\Eloquent\Builder;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleItem\Contracts\Data\CompositionData;

class Composition extends PackageManagement implements ContractsComposition
{
    protected string $__entity = 'Composition';
    public static $composition_model;

    public function prepareStoreComposition(CompositionData $composition_dto){
        $add = [
            'name'       => $composition_dto->name,
            'unit_scale' => $composition_dto->unit_scale,
            'unit_id'    => $composition_dto->unit_id,
            'unit_name'  => $composition_dto->unit_name
        ];

        if (isset($composition_dto->id)) {
            $guard = ['id' => $composition_dto->id];
            $create = [$guard,$add];
        }else{
            $create = [$add];
        }

        $composition = $this->composition()->updateOrCreate(...$create);
        $this->fillingProps($composition,$composition_dto->props);
        $composition->save();
        return static::$composition_model = $composition;
    }

    public function composition(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->CompositionModel()->conditionals($conditionals);
    }
}
