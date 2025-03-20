<?php

namespace Gii\ModuleItem\Contracts;

use Illuminate\Database\Eloquent\Model;
use Zahzah\ModuleWarehouse\Contracts\Stock;

interface ItemStock extends Stock{
    public function prepareStoreItemStock(? array $attributes = null): Model;
}
