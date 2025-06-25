<?php

use Hanafalah\ModuleItem\Controllers\API\InventoryAsset\InventoryAssetController;
use Illuminate\Support\Facades\Route;

Route::apiResource('inventory-asset', InventoryAssetController::class)
    ->parameters(['inventory-asset' => 'id']);