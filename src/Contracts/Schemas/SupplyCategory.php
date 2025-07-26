<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\SupplyCategoryData;
//use Hanafalah\ModuleItem\Contracts\Data\SupplyCategoryUpdateData;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\SupplyCategory
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateSupplyCategory(?SupplyCategoryData $supply_category_dto = null)
 * @method Model prepareUpdateSupplyCategory(SupplyCategoryData $supply_category_dto)
 * @method bool deleteSupplyCategory()
 * @method bool prepareDeleteSupplyCategory(? array $attributes = null)
 * @method mixed getSupplyCategory()
 * @method ?Model prepareShowSupplyCategory(?Model $model = null, ?array $attributes = null)
 * @method array showSupplyCategory(?Model $model = null)
 * @method Collection prepareViewSupplyCategoryList()
 * @method array viewSupplyCategoryList()
 * @method LengthAwarePaginator prepareViewSupplyCategoryPaginate(PaginateData $paginate_dto)
 * @method array viewSupplyCategoryPaginate(?PaginateData $paginate_dto = null)
 * @method array storeSupplyCategory(?SupplyCategoryData $supply_category_dto = null)
 * @method Collection prepareStoreMultipleSupplyCategory(array $datas)
 * @method array storeMultipleSupplyCategory(array $datas)
 */

interface SupplyCategory extends ItemStuff{}