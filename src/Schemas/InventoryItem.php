<?php

namespace Hanafalah\ModuleItem\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleItem\{
    Supports\BaseModuleItem
};
use Hanafalah\ModuleItem\Contracts\Schemas\InventoryItem as ContractsInventoryItem;
use Hanafalah\ModuleItem\Contracts\Data\InventoryItemData;

class InventoryItem extends BaseModuleItem implements ContractsInventoryItem
{
    protected string $__entity = 'InventoryItem';
    public static $inventory_item_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'inventory_item',
            'tags'     => ['inventory_item', 'inventory_item-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreInventoryItem(InventoryItemData $unicode_dto): Model{            
        $add = [
            'name' => $unicode_dto->name,
            'flag' => $unicode_dto->flag,
            'label' => $unicode_dto->label
        ];
        if (isset($unicode_dto->id)){
            $guard  = ['id' => $unicode_dto->id];
            $create = [$guard,$add];
        }else{
            $create = [$add];
        }
        $unicode = $this->usingEntity()->firstOrCreate(...$create);
        $this->fillingProps($unicode, $unicode_dto->props);
        $unicode->save();
        return static::$inventory_item_model = $unicode;
    }

    public function unicode(mixed $conditionals = null): Builder{
        return parent::generalSchemaModel($conditionals)->when(isset(request()->flag),function($query){
            return $query->flagIn(request()->flag);
        })->whereNull('parent_id');
    }

    //OVERIDING DATA MANAGEMENT
    public function generalPrepareStore(mixed $dto = null): Model{
        if (is_array($dto)) $dto = $this->requestDTO(config("app.contracts.{$this->__entity}Data",null));
        $model = $this->prepareStoreInventoryItem($dto);
        return $this->staticEntity($model);
    }

    public function generalSchemaModel(mixed $conditionals = null): Builder{
        return $this->unicode($conditionals);
    }
}