<?php

namespace Hanafalah\ModuleItem\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleItem\{
    Supports\BaseModuleItem
};
use Hanafalah\ModuleItem\Contracts\Schemas\InventoryAsset as ContractsInventoryAsset;
use Hanafalah\ModuleItem\Contracts\Data\InventoryAssetData;

class InventoryAsset extends BaseModuleItem implements ContractsInventoryAsset
{
    protected string $__entity = 'InventoryAsset';
    public static $inventory_asset_model;
    //protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'inventory_asset',
            'tags'     => ['inventory_asset', 'inventory_asset-index'],
            'duration' => 24 * 60
        ]
    ];

    public function prepareStoreInventoryAsset(InventoryAssetData $inventory_asset_dto): Model{
        $add = [
            'name' => $inventory_asset_dto->name
        ];
        $guard  = ['id' => $inventory_asset_dto->id];
        $create = [$guard, $add];
        // if (isset($inventory_asset_dto->id)){
        //     $guard  = ['id' => $inventory_asset_dto->id];
        //     $create = [$guard, $add];
        // }else{
        //     $create = [$add];
        // }

        $inventory_asset = $this->usingEntity()->updateOrCreate(...$create);
        $this->fillingProps($inventory_asset,$inventory_asset_dto->props);
        $inventory_asset->save();
        return static::$inventory_asset_model = $inventory_asset;
    }
}