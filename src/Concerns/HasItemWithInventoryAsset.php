<?php

namespace Hanafalah\ModuleItem\Concerns;

trait HasItemWithInventoryAsset
{
    use HasItem;

    public static function bootHasHasItemWithInventoryAsset()
    {
        static::created(function ($query) {
            parent::created($query);
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
