<?php

namespace Hanafalah\ModuleItem\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Contracts\DataManagement;

interface CardStock extends DataManagement
{
    public function prepareStoreCardStock(?array $attributes = null): Model;
    public function storeCardStock(): array;
    public function showUsingRelation(): array;
    public function prepareShowCardStock(?Model $model = null, ?array $attributes = null): Model;
    public function showCardStock(?Model $model = null): array;
    public function prepareViewCardStockList(?array $attributes = null): Collection;
    public function viewCardStockList(): array;
    public function prepareViewCardStockPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): LengthAwarePaginator;
    public function viewCardStockPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): array;
    public function getCardStock(): mixed;
    public function cardStock(mixed $conditionals = null): Builder;
}
