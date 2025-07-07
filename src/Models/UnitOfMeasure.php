<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\UnitOfMeasure\{ShowUnitOfMeasure, ViewUnitOfMeasure};

class UnitOfMeasure extends ItemStuff
{
    protected $table = 'unicodes';

    public function getViewResource(){return ViewUnitOfMeasure::class;}
    public function getShowResource(){return ShowUnitOfMeasure::class;}
}
