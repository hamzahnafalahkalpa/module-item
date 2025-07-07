<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\CompositionUnit\{
    ViewCompositionUnit,
    ShowCompositionUnit
};

class CompositionUnit extends ItemStuff
{
    protected $table = 'unicodes';

    public function getViewResource(){
        return ViewCompositionUnit::class;
    }

    public function getShowResource(){
        return ShowCompositionUnit::class;
    }
}
