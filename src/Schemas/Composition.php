<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\{
    Composition as ContractsComposition,
};
use Hanafalah\ModuleItem\Resources\Composition\{
    ViewComposition
};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class Composition extends PackageManagement implements ContractsComposition
{
    protected array $__guard   = ['id'];
    protected array $__add     = ['name', 'unit_scale', 'unit_id', 'unit_name'];
    protected string $__entity = 'Composition';
    public static $composition_model;

    protected array $__resources = [
        'view' => ViewComposition::class,
    ];

    public function prepareStoreComposition(?array $attributes = null)
    {
        $attributes ??= request()->all();

        $itemStuff = $this->ItemStuffModel()->find($attributes['unit_id']);

        $create = [
            'name'       => $attributes['name'],
            'unit_scale' => $attributes['unit_scale'],
            'unit_id'    => $attributes['unit_id'],
            'unit_name'  => $itemStuff->name
        ];

        if (isset($attributes['id'])) {
            $create['id'] = $attributes['id'];
        }

        $composition = $this->composition()->updateOrCreate($create);
        static::$composition_model = $composition;
        return $composition;
    }

    public function composition(mixed $conditionals = null): Builder
    {
        $this->booting();
        return $this->CompositionModel()->conditionals($conditionals);
    }
}
