<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleItem\Contracts\Data\CompositionData;

interface Composition extends DataManagement
{
    public function prepareStoreComposition(CompositionData $composition_dto);
    public function composition(mixed $conditionals = null): Builder;
}
