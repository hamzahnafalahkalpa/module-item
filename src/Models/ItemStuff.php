<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\ItemStuff\{ShowItemStuff, ViewItemStuff};
use Hanafalah\LaravelSupport\Models\Unicode\Unicode;

class ItemStuff extends Unicode
{
    protected $table = 'unicodes';

    public function getViewResource(){return ViewItemStuff::class;}
    public function getShowResource(){return ShowItemStuff::class;}
}
