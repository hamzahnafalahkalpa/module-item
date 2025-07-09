<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\SellingFormData;
use Hanafalah\ModuleItem\Contracts\Schemas\SellingForm as ContractsSellingForm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SellingForm extends ItemStuff implements ContractsSellingForm
{
    protected string $__entity = 'SellingForm';
    public static $selling_form_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'selling_form',
            'tags'     => ['selling_form', 'selling_form-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreSellingForm(SellingFormData $selling_form_dto): Model{
        $selling_form_model = $this->prepareStoreUnicode($selling_form_dto);
        return static::$selling_form_model = $selling_form_model;
    }

    public function sellingForm(mixed $conditionals = null): Builder{
        return $this->unicode($conditionals);
    }
}