<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleItem\Contracts\Data\ItemStuffData;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\ItemStuff
 * @method bool deleteItemStuff()
 * @method bool prepareDeleteItemStuff(? array $attributes = null)
 * @method mixed getItemStuff()
 * @method ?Model prepareShowItemStuff(?Model $model = null, ?array $attributes = null)
 * @method array showItemStuff(?Model $model = null)
 * @method Collection prepareViewItemStuffList()
 * @method array viewItemStuffList()
 * @method LengthAwarePaginator prepareViewItemStuffPaginate(PaginateData $paginate_dto)
 * @method array viewItemStuffPaginate(?PaginateData $paginate_dto = null)
 * @method array storeItemStuff(? ItemStuffData $project_dto = null);
 */
interface ItemStuff extends DataManagement
{
    public function prepareStoreItemStuff(ItemStuffData $item_stuff_dto): Model;
    public function itemStuff(mixed $conditionals = null): Builder;    
}
