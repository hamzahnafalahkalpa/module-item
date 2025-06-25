<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\InventoryAssetData;
//use Hanafalah\ModuleItem\Contracts\Data\InventoryAssetUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\InventoryAsset
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateInventoryAsset(?InventoryAssetData $inventory_asset_dto = null)
 * @method Model prepareUpdateInventoryAsset(InventoryAssetData $inventory_asset_dto)
 * @method bool deleteInventoryAsset()
 * @method bool prepareDeleteInventoryAsset(? array $attributes = null)
 * @method mixed getInventoryAsset()
 * @method ?Model prepareShowInventoryAsset(?Model $model = null, ?array $attributes = null)
 * @method array showInventoryAsset(?Model $model = null)
 * @method Collection prepareViewInventoryAssetList()
 * @method array viewInventoryAssetList()
 * @method LengthAwarePaginator prepareViewInventoryAssetPaginate(PaginateData $paginate_dto)
 * @method array viewInventoryAssetPaginate(?PaginateData $paginate_dto = null)
 * @method array storeInventoryAsset(?InventoryAssetData $inventory_asset_dto = null)
 * @method Collection prepareStoreMultipleInventoryAsset(array $datas)
 * @method array storeMultipleInventoryAsset(array $datas)
 */

interface InventoryAsset extends DataManagement
{
    public function prepareStoreInventoryAsset(InventoryAssetData $inventory_asset_dto): Model;
}