<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\NetUnit\{ShowNetUnit, ViewNetUnit};

class NetUnit extends ItemStuff
{
    protected $table = 'unicodes';

    public function getViewResource(){return ViewNetUnit::class;}
    public function getShowResource(){return ShowNetUnit::class;}
}
