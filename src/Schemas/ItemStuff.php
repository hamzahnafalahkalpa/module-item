<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\{
    ItemStuff as ContractsItemStuff
};
use Hanafalah\ModuleItem\Resources\ItemStuff\{
    ViewItemStuff
};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ItemStuff extends PackageManagement implements ContractsItemStuff
{
    protected array $__guard   = ['id'];
    protected array $__add     = ['name', 'flag', 'parent_id'];
    protected string $__entity = 'ItemStuff';
    public static $item_stuff_model;

    protected array $__resources = [
        'view'          => ViewItemStuff::class
    ];



    protected array $__cache = [
        'index' => [
            'name'     => 'item-stuff',
            'tags'     => ['item-stuff', 'item-stuff-index'],
            'forever'  => true
        ]
    ];

    private function localAddSuffixCache(mixed $suffix): void
    {
        $this->addSuffixCache($this->__cache['index'], "item-stuff-index", $suffix);
    }

    public function prepareViewItemStuffList(mixed $flag, mixed $attributes = null): Collection
    {
        $attributes ??= request()->all();
        $this->localAddSuffixCache(implode('-', $this->mustArray($flag)));
        return $this->cacheWhen(!$this->isSearch(), $this->__cache['index'], function () use ($flag, $attributes) {
            return $this->itemStuff($flag)->get();
        });
    }

    public function viewItemStuffList(mixed $flag): array
    {
        return $this->transforming($this->__resources['view'], function () use ($flag) {
            return $this->prepareViewItemStuffList($flag);
        });
    }

    public function viewMultipleItemStuffList(mixed $flags): array
    {
        $flags = $this->mustArray($flags);
        $response = [];
        foreach ($flags as $flag) {
            $response[$flag] = $this->transforming($this->__resources['view'], function () use ($flag) {
                return $this->prepareViewItemStuffList($flag);
            });
        }
        return $response;
    }

    public function getItemStuff(): mixed
    {
        return static::$item_stuff_model;
    }

    public function itemStuff(mixed $flag, mixed $conditionals = null): Builder
    {
        $this->booting();
        $flag = $this->mustArray($flag);
        return $this->ItemStuffModel()->flagIn($flag)->with('childs')
            ->conditionals($conditionals)->orderBy('name', 'asc');
    }
}
