<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\ItemStockData;
use Hanafalah\ModuleItem\Contracts\Schemas\{
    ItemStock as ContractsItemStock
};
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleWarehouse\Schemas\Stock;

class ItemStock extends Stock implements ContractsItemStock
{
    protected string $__entity = 'ItemStock';
    public $item_stock_model;

    public function prepareStoreItemStock(ItemStockData $item_stock_dto): Model{
        return parent::prepareStoreStock($item_stock_dto);
    }
}
