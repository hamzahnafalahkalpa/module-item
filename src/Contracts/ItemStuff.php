<?php

namespace Gii\ModuleItem\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Zahzah\LaravelSupport\Contracts\DataManagement;

interface ItemStuff extends DataManagement {
    public function prepareViewItemStuffList(mixed $flag, mixed $attributes = null): Collection;
    public function viewItemStuffList(mixed $flag): array;
    public function viewMultipleItemStuffList(mixed $flag): array;
    public function getItemStuff(): mixed;
    public function itemStuff(mixed $flag, mixed $conditionals = null): Builder;
}
