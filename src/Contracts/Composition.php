<?php

namespace Gii\ModuleItem\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Zahzah\LaravelSupport\Contracts\DataManagement;

interface Composition extends DataManagement {
    public function prepareStoreComposition(? array $attributes = null);
    public function composition(mixed $conditionals = null): Builder;
    
}
