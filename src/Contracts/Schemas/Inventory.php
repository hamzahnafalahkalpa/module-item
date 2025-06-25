<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\InventoryData;
//use Hanafalah\ModuleItem\Contracts\Data\InventoryUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\Inventory
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateInventory(?InventoryData $inventory_dto = null)
 * @method Model prepareUpdateInventory(InventoryData $inventory_dto)
 * @method bool deleteInventory()
 * @method bool prepareDeleteInventory(? array $attributes = null)
 * @method mixed getInventory()
 * @method ?Model prepareShowInventory(?Model $model = null, ?array $attributes = null)
 * @method array showInventory(?Model $model = null)
 * @method Collection prepareViewInventoryList()
 * @method array viewInventoryList()
 * @method LengthAwarePaginator prepareViewInventoryPaginate(PaginateData $paginate_dto)
 * @method array viewInventoryPaginate(?PaginateData $paginate_dto = null)
 * @method array storeInventory(?InventoryData $inventory_dto = null)
 * @method Collection prepareStoreMultipleInventory(array $datas)
 * @method array storeMultipleInventory(array $datas)
 */

interface Inventory extends DataManagement
{
    public function prepareStoreInventory(InventoryData $inventory_dto): Model;
}