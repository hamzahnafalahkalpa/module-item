<?php

namespace Hanafalah\ModuleItem\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleItem\{
    Supports\BaseModuleItem
};
use Hanafalah\ModuleItem\Contracts\Schemas\SellingForm as ContractsSellingForm;
use Hanafalah\ModuleItem\Contracts\Data\SellingFormData;

class SellingForm extends BaseModuleItem implements ContractsSellingForm
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
        $add = [
            'name' => $selling_form_dto->name
        ];
        $guard  = ['id' => $selling_form_dto->id];
        $create = [$guard, $add];
        // if (isset($selling_form_dto->id)){
        //     $guard  = ['id' => $selling_form_dto->id];
        //     $create = [$guard, $add];
        // }else{
        //     $create = [$add];
        // }

        $selling_form = $this->usingEntity()->updateOrCreate(...$create);
        $this->fillingProps($selling_form,$selling_form_dto->props);
        $selling_form->save();
        return static::$selling_form_model = $selling_form;
    }
}