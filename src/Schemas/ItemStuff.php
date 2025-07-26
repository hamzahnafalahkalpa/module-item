<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\LaravelSupport\Schemas\Unicode;
use Hanafalah\ModuleItem\Contracts\Data\ItemStuffData;
use Hanafalah\ModuleItem\Contracts\Schemas\ItemStuff as SchemasItemStuff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ItemStuff extends Unicode implements SchemasItemStuff
{
    protected string $__entity = 'ItemStuff';
    public $item_stuff_model;
    protected mixed $__order_by_created_at = false; //asc, desc, false

    protected array $__cache = [
        'index' => [
            'name'     => 'item_stuff',
            'tags'     => ['item_stuff', 'item_stuff-index'],
            'forever'  => true
        ]
    ];
    
    public function prepareStoreItemStuff(ItemStuffData $item_stuff_dto): Model{
        $item_stuff = $this->prepareStoreUnicode($item_stuff_dto);
        return $this->item_stuff_model = $item_stuff;
    }

    public function itemStuff(mixed $conditionals = null): Builder{
        return $this->unicode($conditionals);
    }
}
