<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

interface Composition extends DataManagement
{
    public function prepareStoreComposition(?array $attributes = null);
    public function composition(mixed $conditionals = null): Builder;
}
