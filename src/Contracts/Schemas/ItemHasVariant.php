<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Data\PaginateData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\ModuleItem\Contracts\Data\ItemHasVariantData;

/**
 * @see \Hanafalah\ModuleItem\Schemas\ItemHasVariant
 * @method self conditionals(mixed $conditionals = null)
 * @method bool deleteItemHasVariant()
 * @method bool prepareDeleteItemHasVariant(? array $attributes = null)
 * @method mixed getItemHasVariant()
 * @method array storeItemHasVariant(? ItemHasVariantData $ItemHasVariant_dto = null)
 * @method ?Model prepareShowItemHasVariant(?Model $model = null, ?array $attributes = null)
 * @method array showItemHasVariant(?Model $model = null)
 * @method Collection prepareViewItemHasVariantList()
 * @method array viewItemHasVariantList()
 * @method LengthAwarePaginator prepareViewItemHasVariantPaginate(PaginateData $paginate_dto)
 * @method array viewItemHasVariantPaginate(?PaginateData $paginate_dto = null)
 */
interface ItemHasVariant extends DataManagement
{
    public function prepareStoreItemHasVariant(ItemHasVariantData $item_has_variant_dto): Model;
}
