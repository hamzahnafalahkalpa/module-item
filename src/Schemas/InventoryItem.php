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

    public function prepareStore(InventoryItemData $inventory_item_dto){
        return $this->prepareStoreInventoryItem($inventory_item_dto);
    }

    public function prepareStoreInventoryItem(InventoryItemData $inventory_item_dto): Model{            
        $add = [
            'name'  => $inventory_item_dto->name,
            'flag'  => $inventory_item_dto->flag,
            'label' => $inventory_item_dto->label
        ];
        $guard  = ['id' => $inventory_item_dto->id];
        $create = [$guard,$add];
        $inventory_item = $this->usingEntity()->firstOrCreate(...$create);
        $this->fillingProps($inventory_item, $inventory_item_dto->props);
        $inventory_item->save();
        return static::$inventory_item_model = $inventory_item;
    }

    public function inventoryItem(mixed $conditionals = null): Builder{
        return parent::generalSchemaModel($conditionals)->when(isset(request()->flag),function($query){
            return $query->flagIn(request()->flag);
        });
    }

    //OVERIDING DATA MANAGEMENT
    public function generalPrepareStore(mixed $dto = null): Model{
        if (is_array($dto)) $dto = $this->requestDTO(config("app.contracts.{$this->__entity}Data",null));
        $model = $this->prepareStoreInventoryItem($dto);
        return $this->staticEntity($model);
    }

    public function generalSchemaModel(mixed $conditionals = null): Builder{
        return $this->inventoryItem($conditionals);
    }
}