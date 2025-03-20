<?php

namespace Gii\ModuleItem\Concerns;

trait HasItemStock{
    public function stock(){return $this->morphOneModel('ItemStock','subject');}
    public function stocks(){return $this->morphManyModel('ItemStock','subject');}
}