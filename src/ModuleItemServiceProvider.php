<?php

namespace Gii\ModuleItem;

use Gii\ModulePayer\{
    Models\Item,
    Schemas\Item as SchemaPayer,
};
use Zahzah\LaravelSupport\Providers\BaseServiceProvider;

class ModuleItemServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMainClass(ModuleItem::class)
             ->registerCommandService(Providers\CommandServiceProvider::class)
             ->registers([
                '*',
                'Services'  => function(){
                    $this->binds([
                        Contracts\ModuleItem::class  => ModuleItem::class,
                        Contracts\Item::class        => Schemas\Item::class,
                        Contracts\ItemStuff::class   => Schemas\ItemStuff::class,
                        Contracts\ItemStock::class   => Schemas\ItemStock::class,
                        Contracts\Composition::class => Schemas\Composition::class,
                        Contracts\CardStock::class => Schemas\CardStock::class,
                    ]);
                },
             ]);
    }

    protected function dir(): string{
        return __DIR__.'/';
    }

    protected function migrationPath(string $path = ''): string{
        return database_path($path);
    }
}
