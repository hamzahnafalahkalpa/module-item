<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

interface ItemStuff extends DataManagement
{
    public function getItemStuff(): mixed;
    public function prepareViewItemStuffList(mixed $flag, mixed $attributes = null): Collection;
    public function viewItemStuffList(mixed $flag): array;
    public function viewMultipleItemStuffList(mixed $flags): array;
    public function itemStuff(mixed $flag, mixed $conditionals = null): Builder;    
}
