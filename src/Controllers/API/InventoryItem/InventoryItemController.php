<?php

namespace Hanafalah\ModuleItem\Controllers\API\InventoryItem;

use Hanafalah\ModuleItem\Contracts\Schemas\InventoryItem;
use Hanafalah\ModuleItem\Controllers\API\ApiController;
use Hanafalah\ModuleItem\Requests\API\InventoryItem\{
    ViewRequest, StoreRequest, DeleteRequest
};

class InventoryItemController extends ApiController{
    public function __construct(
        protected InventoryItem $__inventoryitem_schema
    ){
        parent::__construct();
    }

    public function index(ViewRequest $request){
        return $this->__inventoryitem_schema->viewInventoryItemList();
    }

    public function store(StoreRequest $request){
        return $this->__inventoryitem_schema->storeInventoryItem();
    }

    public function destroy(DeleteRequest $request){
        return $this->__inventoryitem_schema->deleteInventoryItem();
    }
}