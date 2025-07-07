<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\Brand\{ShowBrand, ViewBrand};
use Hanafalah\LaravelSupport\Models\Unicode\Unicode;

class Brand extends Unicode
{
    protected $table = 'unicodes';

    public function getViewResource(){return ViewBrand::class;}
    public function getShowResource(){return ShowBrand::class;}
}
