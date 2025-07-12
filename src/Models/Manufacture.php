<?php

namespace Hanafalah\ModuleItem\Models;

use Hanafalah\ModuleItem\Resources\Manufacture\ViewManufacture;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Manufacture extends BaseModel
{
    use HasUlids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
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
