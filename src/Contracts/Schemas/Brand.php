<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\BrandData;
//use Hanafalah\ModuleItem\Contracts\Data\BrandUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\Brand
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateBrand(?BrandData $brand_dto = null)
 * @method Model prepareUpdateBrand(BrandData $brand_dto)
 * @method bool deleteBrand()
 * @method bool prepareDeleteBrand(? array $attributes = null)
 * @method mixed getBrand()
 * @method ?Model prepareShowBrand(?Model $model = null, ?array $attributes = null)
 * @method array showBrand(?Model $model = null)
 * @method Collection prepareViewBrandList()
 * @method array viewBrandList()
 * @method LengthAwarePaginator prepareViewBrandPaginate(PaginateData $paginate_dto)
 * @method array viewBrandPaginate(?PaginateData $paginate_dto = null)
 * @method Model prepareStoreBrand(BrandData $brand_dto)
 * @method array storeBrand(?BrandData $brand_dto = null)
 * @method Collection prepareStoreMultipleBrand(array $datas)
 * @method array storeMultipleBrand(array $datas)
 */

interface Brand extends ItemStuff{}