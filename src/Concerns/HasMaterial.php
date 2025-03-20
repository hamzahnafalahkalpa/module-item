<?php

namespace Gii\ModuleItem\Concerns;

trait HasMaterial{
    public function materials(){
        return $this->belongsToManyModel(
            'Material','BillOfMaterial',
            'reference_id',$this->MaterialModel()->getForeignKey()
        )->where('reference_type',$this->getMorphClass());
    }
}