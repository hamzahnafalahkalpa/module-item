<?php

namespace Hanafalah\ModuleItem\Schemas;

use Hanafalah\ModuleItem\Contracts\Schemas\{
    ItemStuff as ContractsItemStuff
};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ItemStuff extends PackageManagement implements ContractsItemStuff
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

    public function getItemStuff(): mixed{
        return static::$item_stuff_model;
    }

    private function localAddSuffixCache(mixed $suffix): void{
        $this->addSuffixCache($this->__cache['index'], "item-stuff-index", $suffix);
    }

    public function prepareViewItemStuffList(mixed $flag, mixed $attributes = null): Collection{
        $attributes ??= request()->all();
        $this->localAddSuffixCache(implode('-', $this->mustArray($flag)));
        return $this->cacheWhen(!$this->isSearch(), $this->__cache['index'], function () use ($flag, $attributes) {
            return $this->itemStuff($flag)->get();
        });
    }

    public function viewItemStuffList(mixed $flag): array{
        return $this->viewEntityResource(function() use ($flag){
            return $this->prepareViewItemStuffList($flag);
        });
    }

    public function viewMultipleItemStuffList(mixed $flags): array{
        $flags = $this->mustArray($flags);
        $response = [];
        foreach ($flags as $flag) {
            $response[$flag] = $this->viewEntityResource(function() use ($flag){
                return $this->prepareViewItemStuffList($flag);
            });
        }
        return $response;
    }

    public function itemStuff(mixed $flag, mixed $conditionals = null): Builder{
        $this->booting();
        $flag = $this->mustArray($flag);
        return $this->ItemStuffModel()->flagIn($flag)->with('childs')->conditionals($this->mergeCOndition($conditionals ?? []))->orderBy('name', 'asc');
    }
}
