<?php

namespace Hanafalah\ModuleItem\Concerns;

trait HasComposition
{
    public function compositions()
    {
        return $this->belongsToManyModel(
            'Composition',
            'ModelHasComposition',
            'model_id',
            'composition_id'
        );
    }
}
