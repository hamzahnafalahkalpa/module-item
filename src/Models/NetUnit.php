<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\NetUnit\{ShowNetUnit, ViewNetUnit};
use Hanafalah\LaravelSupport\Models\Unicode\Unicode;

class NetUnit extends Unicode
{
    protected $table = 'unicodes';

    public function getViewResource(){return ViewNetUnit::class;}
    public function getShowResource(){return ShowNetUnit::class;}
}
