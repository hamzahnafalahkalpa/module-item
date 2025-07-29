<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\{
    ItemHasVariant as ContractsItemHasVariant
};
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleItem\Contracts\Data\ItemHasVariantData;

class ItemHasVariant extends PackageManagement implements ContractsItemHasVariant
{
    protected string $__entity = 'ItemHasVariant';
    public $item_has_variant;

    protected array $__cache = [
        'index' => [
            'name'     => 'item_has_variant',
            'tags'     => ['item_has_variant', 'item_has_variant-index'],
            'duration' => 60 * 12
        ]
    ];

    public function prepareStoreItemHasVariant(ItemHasVariantData $item_has_variant_dto): Model{        
        switch (true) {
            case isset($item_has_variant_dto->variant_id) && isset($item_has_variant_dto->variant_type):
                $variant = $this->{$item_has_variant_dto->variant_type.'Model'}()->findOrFail($item_has_variant_dto->variant_id);
            break;
            case isset($item_has_variant_dto->variant):
                $variant = $this->schemaContract('variant')->prepareStoreVariant($item_has_variant_dto->variant);
                $item_has_variant_dto->variant_id     = $variant->getKey();
                $item_has_variant_dto->variant_type ??= $variant->getMorphClass();
            break;
        }
        if (isset($variant)){
            $item_has_variant_dto->props['prop_variant'] = $variant->toViewApi()->only(['id','name','flag','label']);
        }
        $item_has_variant_dto->variant_name ??= $variant?->name ?? null;
        if (!isset($item_has_variant_dto->variant_name)) throw new \Exception('No variant name provided', 422);

        $add = [
            'variant_name'   => $item_has_variant_dto->variant_name
        ];
        
        if (isset($item_has_variant_dto->id)){
            $guard = ['id' => $item_has_variant_dto->id];
        }else{
            $guard = [
                'item_id' => $item_has_variant_dto->item_id, 
                'variant_label'  => $item_has_variant_dto->variant_label,
                'variant_type'   => $item_has_variant_dto->variant_type,
                'variant_id'     => $item_has_variant_dto->variant_id
            ];
        }
        $item_has_variant = $this->usingEntity()->updateOrCreate($guard,$add);
        $this->fillingProps($item_has_variant,$item_has_variant_dto->props);
        $item_has_variant->save();
        return $this->item_has_variant = $item_has_variant;
    }
}
