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
        $itemStuff = $this->ItemStuffModel()->withoutGlobalScope('flag')->find($composition_dto->unit_id);

        $create = [
            'name'       => $composition_dto->name,
            'unit_scale' => $composition_dto->unit_scale,
            'unit_id'    => $composition_dto->unit_id,
            'unit_name'  => $itemStuff->name
        ];

        if (isset($composition_dto->id)) {
            $create['id'] = $composition_dto->id;
        }

        $composition = $this->composition()->updateOrCreate($create);
        static::$composition_model = $composition;
        return $composition;
    }

    public function composition(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->CompositionModel()->conditionals($conditionals);
    }
}
