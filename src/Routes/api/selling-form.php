<?php

use Hanafalah\ModuleItem\Controllers\API\SellingForm\SellingFormController;
use Illuminate\Support\Facades\Route;

Route::apiResource('selling-form', SellingFormController::class)
    ->parameters(['selling-form' => 'id']);