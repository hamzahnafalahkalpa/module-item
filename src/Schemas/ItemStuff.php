<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleItem\Contracts\Data\ItemStuffData;
use Hanafalah\ModuleItem\Contracts\Schemas\ItemStuff as SchemasItemStuff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ItemStuff extends PackageManagement implements SchemasItemStuff
{
    protected string $__entity = 'ItemStuff';
    public static $item_stuff_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'item-stuff',
            'tags'     => ['item-stuff', 'item-stuff-index'],
            'forever'  => true
        ]
    ];

    
    public function prepareStoreItemStuff(ItemStuffData $item_stuff_dto): Model{
        $add = [
            'name' => $item_stuff_dto->name,
            'flag' => $item_stuff_dto->flag
        ];
        if (isset($item_stuff_dto->id)){
            $guard = ['id' => $item_stuff_dto->id];
            $create = [$guard,$add];
        }else{
            $create = [$add];
        }
        $item_stuff = $this->usingEntity()->updateOrCreate(...$create);
        $this->fillingProps($item_stuff,$item_stuff_dto->props);
        $item_stuff->save();
        return static::$item_stuff_model = $item_stuff;
    }

    public function itemStuff(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->{$this->__entity.'Model'}()->whereNull('parent_id')
                    ->conditionals($this->mergeCondition($conditionals))
                    ->withParameters()->orderBy('name','asc');
    }
}
