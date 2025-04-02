<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\{
    ItemStock as ContractsItemStock
};
use Hanafalah\ModuleItem\Resources\ItemStock\ShowItemStock;
use Hanafalah\ModuleItem\Resources\ItemStock\ViewItemStock;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleWarehouse\Schemas\Stock;

class ItemStock extends Stock implements ContractsItemStock
{
    protected string $__entity = 'ItemStock';
    public static $item_stock_model;

    protected array $__resources = [
        'view' => ViewItemStock::class,
        'show' => ShowItemStock::class
    ];

    public function prepareStoreItemStock(?array $attributes = null): Model
    {
        return parent::prepareStoreStock($attributes);
    }
}
