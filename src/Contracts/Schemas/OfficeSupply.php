<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\OfficeSupplyData;
//use Hanafalah\ModuleItem\Contracts\Data\OfficeSupplyUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\OfficeSupply
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateOfficeSupply(?OfficeSupplyData $office_supply_dto = null)
 * @method Model prepareUpdateOfficeSupply(OfficeSupplyData $office_supply_dto)
 * @method bool deleteOfficeSupply()
 * @method bool prepareDeleteOfficeSupply(? array $attributes = null)
 * @method mixed getOfficeSupply()
 * @method ?Model prepareShowOfficeSupply(?Model $model = null, ?array $attributes = null)
 * @method array showOfficeSupply(?Model $model = null)
 * @method Collection prepareViewOfficeSupplyList()
 * @method array viewOfficeSupplyList()
 * @method LengthAwarePaginator prepareViewOfficeSupplyPaginate(PaginateData $paginate_dto)
 * @method array viewOfficeSupplyPaginate(?PaginateData $paginate_dto = null)
 * @method Model prpeareStoreOfficeSupply(OfficeSupplyData $office_supply_dto)
 * @method array storeOfficeSupply(?OfficeSupplyData $office_supply_dto = null)
 * @method Collection prepareStoreMultipleOfficeSupply(array $datas)
 * @method array storeMultipleOfficeSupply(array $datas)
 */

interface OfficeSupply extends InventoryItem{}