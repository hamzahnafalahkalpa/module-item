<?php

namespace Hanafalah\ModuleItem\Contracts\Schemas;

use Hanafalah\ModuleItem\Contracts\Data\ItemStockData;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleWarehouse\Contracts\Schemas\Stock;

interface ItemStock extends Stock{
    public function prepareStoreItemStock(ItemStockData $item_stock_dto): Model;
}
