<?php

namespace Hanafalah\ModuleItem\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;

class BillOfMaterial extends BaseModel
{
    use SoftDeletes, HasProps;

    protected $list = [
        'id', 'reference_type', 'reference_id', 'item_id', 'material_id', 'props'
    ];
    protected $show = [];

    public function reference(){return $this->morphTo();}
    public function item(){return $this->belongsToModel('Item');}
    public function material(){return $this->belongsToModel('Material');}
}
