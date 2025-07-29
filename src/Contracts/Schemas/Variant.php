<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\VariantData;
//use Hanafalah\ModuleItem\Contracts\Data\VariantUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\Variant
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateVariant(?VariantData $variant_dto = null)
 * @method Model prepareUpdateVariant(VariantData $variant_dto)
 * @method bool deleteVariant()
 * @method bool prepareDeleteVariant(? array $attributes = null)
 * @method mixed getVariant()
 * @method ?Model prepareShowVariant(?Model $model = null, ?array $attributes = null)
 * @method array showVariant(?Model $model = null)
 * @method Collection prepareViewVariantList()
 * @method array viewVariantList()
 * @method LengthAwarePaginator prepareViewVariantPaginate(PaginateData $paginate_dto)
 * @method array viewVariantPaginate(?PaginateData $paginate_dto = null)
 * @method array storeVariant(?VariantData $variant_dto = null)
 * @method Collection prepareStoreMultipleVariant(array $datas)
 * @method array storeMultipleVariant(array $datas)
 */

interface Variant extends ItemStuff
{
    public function prepareStoreVariant(VariantData $variant_dto): Model;
    public function variant(mixed $conditionals = null): Builder;
}