<?php

namespace Hanafalah\ModuleItem\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleItem\{
    Supports\BaseModuleItem
};
use Hanafalah\ModuleItem\Contracts\Schemas\Inventory as ContractsInventory;
use Hanafalah\ModuleItem\Contracts\Data\InventoryData;

class Inventory extends BaseModuleItem implements ContractsInventory
{
    protected string $__entity = 'Inventory';
    public static $inventory_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'inventory',
            'tags'     => ['inventory', 'inventory-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreInventory(InventoryData $inventory_dto): Model{
        $add = [
            'name' => $inventory_dto->name
        ];
        $guard  = ['id' => $inventory_dto->id];
        $create = [$guard, $add];
        // if (isset($inventory_dto->id)){
        //     $guard  = ['id' => $inventory_dto->id];
        //     $create = [$guard, $add];
        // }else{
        //     $create = [$add];
        // }

        $inventory = $this->usingEntity()->updateOrCreate(...$create);
        $this->fillingProps($inventory,$inventory_dto->props);
        $inventory->save();
        return static::$inventory_model = $inventory;
    }
}