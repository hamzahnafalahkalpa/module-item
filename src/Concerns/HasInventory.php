<?php

namespace Hanafalah\ModuleItem\Concerns;

trait HasInventory
{
    public static function bootHasInventory()
    {
        static::created(function ($query) {
            $query->inventory()->firstOrCreate([
                'reference_id'   => $query->getKey(),
                'reference_type' => $query->getMorphClass()
            ], [
                'name' => $query->name
            ]);
        });

        static::updating(function ($query) {
            $query->item()->updateOrCreate([
                'reference_id'   => $query->getKey(),
                'reference_type' => $query->getMorphClass()
            ], [
                'name' => $query->name
            ]);
        });
    }

    public function inventory(){return $this->morphOneModel('Inventory', 'reference');}
}
