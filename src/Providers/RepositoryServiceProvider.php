<?php

namespace Fatihirday\RepositoryMake\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 *
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/repository.php', 'repository');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->addCommands();
        $this->autoloadRepository();
        $this->config();

        ServiceProvider::addProviderToBootstrapFile(
            RepositoryServiceProvider::class,
            $this->app->getBootstrapProvidersPath(),
        );
    }

    /**
     * @return void
     */
    protected function addCommands(): void
    {
        // Command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Fatihirday\RepositoryMake\Console\Commands\InterfaceCommand::class,
                \Fatihirday\RepositoryMake\Console\Commands\RepositoryCommand::class,
            ]);
        }
    }

    /**
     * @return void
     */
    protected function autoloadRepository(): void
    {
        $fileList = File::glob(app_path('Services/Interfaces/*'));

        foreach ($fileList as $file) {
            $class = Str::of($file)->basename()
                ->before('Interface.php')
                ->prepend('App\Services\%s\\')
                ->append('%s');

            app()->bind(
                sprintf($class, 'Interfaces', 'Interface'),
                sprintf($class, 'Repositories', 'Repository')
            );
        }
    }

    /**
     * @return void
     */
    public function config(): void
    {
        $this->publishes([
            __DIR__.'/../config/repository.php' => config_path('repository.php'),
        ], 'config');
    }
}
