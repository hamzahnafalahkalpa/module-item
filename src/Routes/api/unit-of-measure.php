<?php

use Hanafalah\ModuleItem\Controllers\API\UnitOfMeasure\UnitOfMeasureController;
use Illuminate\Support\Facades\Route;

Route::apiResource('unit-of-measure', UnitOfMeasureController::class)
    ->parameters(['unit-of-measure' => 'id']);