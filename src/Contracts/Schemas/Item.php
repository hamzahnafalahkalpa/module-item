<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Data\PaginateData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleItem\Contracts\Data\ItemData;

/**
 * @see \Hanafalah\ModuleItem\Schemas\Item
 * @method bool deleteItem()
 * @method bool prepareDeleteItem(? array $attributes = null)
 * @method mixed getItem()
 * @method array storeItem(? ItemData $Item_dto = null)
 * @method ?Model prepareShowItem(?Model $model = null, ?array $attributes = null)
 * @method array showItem(?Model $model = null)
 * @method Collection prepareViewItemList()
 * @method array viewItemList()
 * @method LengthAwarePaginator prepareViewItemPaginate(PaginateData $paginate_dto)
 * @method array viewItemPaginate(?PaginateData $paginate_dto = null)
 */
interface Item extends DataManagement
{
    public function prepareStoreItem(ItemData $item_dto): Model;
    public function item(mixed $conditionals = null): Builder;
}
