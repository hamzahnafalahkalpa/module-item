<?php

namespace Hanafalah\ModuleItem;

use Hanafalah\ModulePayer\{
    Models\Item,
    Schemas\Item as SchemaPayer,
};
use Hanafalah\LaravelSupport\Providers\BaseServiceProvider;

class ModuleItemServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(){
        $this->registerMainClass(ModuleItem::class)
            ->registerCommandService(Providers\CommandServiceProvider::class)
            ->registers(['*']);
    }

    protected function dir(): string{
        return __DIR__ . '/';
    }

    protected function migrationPath(string $path = ''): string
    {
        return database_path($path);
    }
}
