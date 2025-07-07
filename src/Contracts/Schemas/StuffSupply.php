<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\StuffSupplyData;
//use Hanafalah\ModuleItem\Contracts\Data\StuffSupplyUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\StuffSupply
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateStuffSupply(?StuffSupplyData $stuff_supply_dto = null)
 * @method Model prepareUpdateStuffSupply(StuffSupplyData $stuff_supply_dto)
 * @method bool deleteStuffSupply()
 * @method bool prepareDeleteStuffSupply(? array $attributes = null)
 * @method mixed getStuffSupply()
 * @method ?Model prepareShowStuffSupply(?Model $model = null, ?array $attributes = null)
 * @method array showStuffSupply(?Model $model = null)
 * @method Collection prepareViewStuffSupplyList()
 * @method array viewStuffSupplyList()
 * @method LengthAwarePaginator prepareViewStuffSupplyPaginate(PaginateData $paginate_dto)
 * @method array viewStuffSupplyPaginate(?PaginateData $paginate_dto = null)
 * @method Model prepareStoreStuffSupply(StuffSupplyData $stuff_supply_dto)
 * @method array storeStuffSupply(?StuffSupplyData $stuff_supply_dto = null)
 * @method Collection prepareStoreMultipleStuffSupply(array $datas)
 * @method array storeMultipleStuffSupply(array $datas)
 */

interface StuffSupply extends InventoryItem{}