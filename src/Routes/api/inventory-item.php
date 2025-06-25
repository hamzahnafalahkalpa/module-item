<?php

use Hanafalah\ModuleItem\Controllers\API\InventoryItem\InventoryItemController;
use Illuminate\Support\Facades\Route;

Route::apiResource('inventory-item', InventoryItemController::class)
    ->parameters(['inventory-item' => 'id']);