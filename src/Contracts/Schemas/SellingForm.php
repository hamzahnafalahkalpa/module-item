<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\SellingFormData;
//use Hanafalah\ModuleItem\Contracts\Data\SellingFormUpdateData;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleItem\Schemas\SellingForm
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateSellingForm(?SellingFormData $selling_form_dto = null)
 * @method Model prepareUpdateSellingForm(SellingFormData $selling_form_dto)
 * @method bool deleteSellingForm()
 * @method bool prepareDeleteSellingForm(? array $attributes = null)
 * @method mixed getSellingForm()
 * @method ?Model prepareShowSellingForm(?Model $model = null, ?array $attributes = null)
 * @method array showSellingForm(?Model $model = null)
 * @method Collection prepareViewSellingFormList()
 * @method array viewSellingFormList()
 * @method LengthAwarePaginator prepareViewSellingFormPaginate(PaginateData $paginate_dto)
 * @method array viewSellingFormPaginate(?PaginateData $paginate_dto = null)
 * @method array storeSellingForm(?SellingFormData $selling_form_dto = null)
 * @method Collection prepareStoreMultipleSellingForm(array $datas)
 * @method array storeMultipleSellingForm(array $datas)
 */

interface SellingForm extends DataManagement
{
    public function prepareStoreSellingForm(SellingFormData $selling_form_dto): Model;
}