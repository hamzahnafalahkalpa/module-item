<?php

namespace Hanafalah\ModuleItem\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleItem\Contracts\Schemas\Variant as ContractsVariant;
use Hanafalah\ModuleItem\Contracts\Data\VariantData;

class Variant extends ItemStuff implements ContractsVariant
{
    protected string $__entity = 'Variant';
    public $variant;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'variant',
            'tags'     => ['variant', 'variant-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreVariant(VariantData $variant_dto): Model{
        $variant = $this->prepareStoreUnicode($variant_dto);
        $this->fillingProps($variant,$variant_dto->props);
        $variant->save();
        return $this->variant = $variant;
    }

    public function variant(mixed $conditionals = null): Builder{
        return $this->itemStuff($conditionals);
    }
}