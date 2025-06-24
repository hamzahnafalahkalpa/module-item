<?php

use Hanafalah\ModuleItem\Controllers\API\NetUnit\NetUnitController;
use Illuminate\Support\Facades\Route;

Route::apiResource('net-unit', NetUnitController::class)
    ->parameters(['net-unit' => 'id']);