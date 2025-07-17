<?php

namespace Hanafalah\ModuleItem\Controllers\API\InventoryAsset;

use Hanafalah\ModuleItem\Contracts\Schemas\InventoryAsset;
use Hanafalah\ModuleItem\Controllers\API\ApiController;
use Hanafalah\ModuleItem\Requests\API\InventoryAsset\{
    ViewRequest, StoreRequest, DeleteRequest
};

class InventoryAssetController extends ApiController{
    public function __construct(
        protected InventoryAsset $__inventoryasset_schema
    ){
        parent::__construct();
    }

    public function index(ViewRequest $request){
        return $this->__inventoryasset_schema->viewInventoryAssetList();
    }

    public function store(StoreRequest $request){
        return $this->__inventoryasset_schema->storeInventoryAsset();
    }

    public function destroy(DeleteRequest $request){
        return $this->__inventoryasset_schema->deleteInventoryAsset();
    }
}