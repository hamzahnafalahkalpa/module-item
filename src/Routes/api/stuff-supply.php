<?php

use Hanafalah\ModuleItem\Controllers\API\StuffSupply\StuffSupplyController;
use Illuminate\Support\Facades\Route;

Route::apiResource('stuff-supply', StuffSupplyController::class)
    ->parameters(['stuff-supply' => 'id']);