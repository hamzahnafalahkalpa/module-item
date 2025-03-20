<?php

namespace Hanafalah\ModuleItem\Concerns;

trait HasItem
{
    public static function bootHasItem()
    {
        static::created(function ($query) {
            $query->item()->firstOrCreate([
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

    public function item()
    {
        return $this->morphOneModel('Item', 'reference');
    }
}
