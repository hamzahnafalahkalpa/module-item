<?php

use Hanafalah\ModuleItem\Controllers\API\Inventory\InventoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('inventory', InventoryController::class)
    ->parameters(['inventory' => 'id']);