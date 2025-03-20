<?php

namespace Hanafalah\ModuleItem\Contracts;

use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleWarehouse\Contracts\Stock;

interface ItemStock extends Stock
{
    public function prepareStoreItemStock(?array $attributes = null): Model;
}
