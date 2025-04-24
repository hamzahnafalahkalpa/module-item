<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleItem\Contracts\Data\CardStockData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @see \Hanafalah\ModuleItem\Schemas\CardStock
 * @method bool deleteCardStock()
 * @method bool prepareDeleteCardStock(? array $attributes = null)
 * @method mixed getCardStock()
 * @method ?Model prepareShowCardStock(?Model $model = null, ?array $attributes = null)
 * @method array showCardStock(?Model $model = null)
 * @method Collection prepareViewCardStockList()
 * @method array viewCardStockList()
 * @method array viewCardStockPaginate(?PaginateData $paginate_dto = null)
 */
interface CardStock extends DataManagement
{
    public function prepareStoreCardStock(CardStockData $card_stock_dto): Model;
    public function storeCardStock(?CardStockData $card_stock_dto = null): array;
    public function prepareViewCardStockPaginate(int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): LengthAwarePaginator;
    public function cardStock(mixed $conditionals = null): Builder;
    
}
