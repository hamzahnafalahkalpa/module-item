<?php

namespace Gii\ModuleItem\Schemas;

use Gii\ModuleItem\Contracts\{
    ItemStock as ContractsItemStock
};
use Gii\ModuleItem\Resources\ItemStock\ShowItemStock;
use Gii\ModuleItem\Resources\ItemStock\ViewItemStock;
use Illuminate\Database\Eloquent\Model;
use Zahzah\ModuleWarehouse\Schemas\Stock;

class ItemStock extends Stock implements ContractsItemStock{
    protected string $__entity = 'ItemStock';
    public static $item_stock_model;

    protected array $__resources = [
        'view' => ViewItemStock::class,
        'show' => ShowItemStock::class   
    ];

    public function prepareStoreItemStock(? array $attributes = null): Model{
        return parent::prepareStoreStock($attributes);
    }
}
