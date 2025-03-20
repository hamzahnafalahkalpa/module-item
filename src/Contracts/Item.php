<?php

namespace Gii\ModuleItem\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Zahzah\LaravelSupport\Contracts\DataManagement;

interface Item extends DataManagement {
    public function prepareStoreItem(? array $attributes = null): Model;
    public function storeItem(): array;
    public function prepareShowItem(?Model $model = null): Model;
    public function showItem(?Model $model = null): array;
    public function prepareViewItemPaginate(mixed $cache_reference_type,? array $morphs = null, int $perPage = 50, array $columns = ['*'], string $pageName = 'page',? int $page = null,? int $total = null): LengthAwarePaginator;
    public function viewItemPaginate(mixed $reference_type, ? array $morphs = null, int $perPage = 50, array $columns = ['*'], string $pageName = 'page',? int $page = null,? int $total = null): array;
    public function prepareFindItem(? array $attributes = null): mixed;
    public function findItem(): mixed;
    public function getItem(): mixed;
    public function item(mixed $conditionals = null): Builder;
    
}
