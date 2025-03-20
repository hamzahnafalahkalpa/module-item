<?php

namespace Gii\ModuleItem\Models;

use Gii\ModuleItem\Resources\Manufacture\ViewManufacture;
use Zahzah\LaravelSupport\Models\BaseModel;

class Manufacture extends BaseModel
{
    protected $list  = ['id', 'name'];

    public function toViewApi(){
        return new ViewManufacture($this);
    }

    public function toShowApi(){
        return new ViewManufacture($this);
    }

    public function modelHasManufacture(){return $this->hasOneModel('ModelHasManufacture');}
    public function modelHasManufactures(){return $this->hasManyModel('ModelHasManufacture');}
}
