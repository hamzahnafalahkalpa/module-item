<?php

namespace Hanafalah\ModuleItem\Controllers\API\Inventory;

use Hanafalah\ModuleItem\Contracts\Schemas\Inventory;
use Hanafalah\ModuleItem\Controllers\API\ApiController;
use Hanafalah\ModuleItem\Requests\API\Inventory\{
    ViewRequest, StoreRequest, DeleteRequest
};

class InventoryController extends ApiController{
    public function __construct(
        protected Inventory $__inventory_schema
    ){
        parent::__construct();
    }

    public function index(ViewRequest $request){
        return $this->__inventory_schema->viewInventoryList();
    }

    public function store(StoreRequest $request){
        return $this->__inventory_schema->storeInventory();
    }

    public function destroy(DeleteRequest $request){
        return $this->__inventory_schema->deleteInventory();
    }
}