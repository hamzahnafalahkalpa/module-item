<?php

namespace Hanafalah\ModuleItem\Controllers\API\StuffSupply;

use Hanafalah\ModuleItem\Contracts\Schemas\StuffSupply;
use Hanafalah\ModuleItem\Controllers\API\ApiController;
use Hanafalah\ModuleItem\Requests\API\StuffSupply\{
    ViewRequest, StoreRequest, DeleteRequest
};

class StuffSupplyController extends ApiController{
    public function __construct(
        protected StuffSupply $__stuffsupply_schema
    ){
        parent::__construct();
    }

    public function index(ViewRequest $request){
        return $this->__stuffsupply_schema->viewStuffSupplyList();
    }

    public function store(StoreRequest $request){
        return $this->__stuffsupply_schema->storeStuffSupply();
    }

    public function destroy(DeleteRequest $request){
        return $this->__stuffsupply_schema->deleteStuffSupply();
    }
}