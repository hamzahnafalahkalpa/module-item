<?php

namespace Gii\ModuleItem\Concerns;

trait HasItemStuff{
    public function itemStuff(){return $this->belongsToModel('ItemStuff');} 
}