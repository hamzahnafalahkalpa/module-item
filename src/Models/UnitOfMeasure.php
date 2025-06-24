<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\UnitOfMeasure\{ShowUnitOfMeasure, ViewUnitOfMeasure};
use Hanafalah\LaravelSupport\Models\Unicode\Unicode;

class UnitOfMeasure extends Unicode
{
    protected $table = 'unicodes';

    public function getViewResource(){return ViewUnitOfMeasure::class;}
    public function getShowResource(){return ShowUnitOfMeasure::class;}
}
