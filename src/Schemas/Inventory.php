<?php

namespace Hanafalah\ModuleItem\Schemas;

use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleItem\{
    Supports\BaseModuleItem
};
use Hanafalah\ModuleItem\Contracts\Schemas\Inventory as ContractsInventory;
use Hanafalah\ModuleItem\Contracts\Data\InventoryData;
use Illuminate\Support\Str;

class Inventory extends BaseModuleItem implements ContractsInventory
{
    protected string $__entity = 'Inventory';
    public $inventory_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'inventory',
            'tags'     => ['inventory', 'inventory-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreInventory(InventoryData $inventory_dto): Model{
        $reference_type   = $inventory_dto->reference_type;
        $reference_schema = config('module-item.inventory_types.'.Str::snake($reference_type).'.schema');        
        if (isset($reference_schema)) {
            $schema_reference = $this->schemaContract(Str::studly($reference_schema));
            $reference = $schema_reference->prepareStore($inventory_dto->reference);
            $inventory_dto->reference_id = $reference->getKey();
            $inventory_dto->props['prop_reference'] = $reference->toViewApi()->resolve();
        }
        $add = [    
            'name'               => $inventory_dto->name,
            'inventory_code'     => $inventory_dto->inventory_code,
            'brand_id'           => $inventory_dto->brand_id,
            'supply_category_id' => $inventory_dto->supply_category_id
        ];
        $guard = isset($inventory_dto->id) 
            ? ['id' => $inventory_dto->id]
            : [
                'reference_type' => $inventory_dto->reference_type, 
                'reference_id'   => $inventory_dto->reference_id
            ];

        $inventory = $this->usingEntity()->updateOrCreate($guard, $add);
        $inventory->refresh();
        if (isset($reference_schema) && method_exists($schema_reference, 'onCreated')) {
            $schema_reference->onCreated($inventory, $reference, $inventory_dto);
        }

        if (isset($inventory_dto->item)){
            $inventory_dto->item->reference_model = $inventory;
            $item = $this->schemaContract('item')->prepareStoreItem($inventory_dto->item);
            $inventory_dto->props['prop_item'] = $item->toViewApi()->resolve();
        }
        $this->fillingProps($inventory,$inventory_dto->props);
        $inventory->save();
        return $inventory;
    }
}