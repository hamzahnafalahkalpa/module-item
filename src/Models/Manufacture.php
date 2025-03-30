<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\Manufacture\ViewManufacture;
use Hanafalah\LaravelSupport\Models\BaseModel;

class Manufacture extends BaseModel
{
    protected $list  = ['id', 'name'];

    public function getViewResource(){
        return ViewManufacture::class;
    }

    public function getShowResource(){
        return ViewManufacture::class;
    }

    public function modelHasManufacture(){return $this->hasOneModel('ModelHasManufacture');}
    public function modelHasManufactures(){return $this->hasManyModel('ModelHasManufacture');}
}
