<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\InventoryItemData;
//use Hanafalah\ModuleItem\Contracts\Data\InventoryItemUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\InventoryItem
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateInventoryItem(?InventoryItemData $inventory_item_dto = null)
 * @method Model prepareUpdateInventoryItem(InventoryItemData $inventory_item_dto)
 * @method bool deleteInventoryItem()
 * @method bool prepareDeleteInventoryItem(? array $attributes = null)
 * @method mixed getInventoryItem()
 * @method ?Model prepareShowInventoryItem(?Model $model = null, ?array $attributes = null)
 * @method array showInventoryItem(?Model $model = null)
 * @method Collection prepareViewInventoryItemList()
 * @method array viewInventoryItemList()
 * @method LengthAwarePaginator prepareViewInventoryItemPaginate(PaginateData $paginate_dto)
 * @method array viewInventoryItemPaginate(?PaginateData $paginate_dto = null)
 * @method array storeInventoryItem(?InventoryItemData $inventory_item_dto = null)
 * @method Collection prepareStoreMultipleInventoryItem(array $datas)
 * @method array storeMultipleInventoryItem(array $datas)
 */

interface InventoryItem extends DataManagement
{
    public function prepareStoreInventoryItem(InventoryItemData $inventory_item_dto): Model;
}