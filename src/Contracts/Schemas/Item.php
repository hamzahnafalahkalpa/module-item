<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Data\PaginateData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleItem\Contracts\Data\ItemData;

interface Item extends DataManagement
{
    public function getItem(): mixed;
    public function prepareStoreItem(ItemData $item_dto): Model;
    public function storeItem(): array;
    public function prepareShowItem(?Model $model = null, ?array $attributes = null): Model;
    public function showItem(?Model $model = null): array;
    public function prepareViewItemPaginate(mixed $cache_reference_type, ?array $morphs = null, PaginateData $paginate_dto): LengthAwarePaginator;
    public function viewItemPaginate(mixed $reference_type, ?array $morphs = null, ?PaginateData $paginate_dto = null): array;
    public function prepareFindItem(?array $attributes = null): mixed;
    public function findItem(): mixed;
    public function item(mixed $conditionals = null): Builder;    
}
