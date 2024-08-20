<?php

namespace Fatihirday\RepositoryMake\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class RepositoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        Artisan::call('make:interface ' . $this->getInterfaceName());

        return parent::handle();
    }

    protected function getStub()
    {
        return __DIR__ . '/../../stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services\Repositories';
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
            ->replaceInterface($stub, $name)
            ->replaceModel($stub, $name)
            ->replaceClass($stub, $name);
    }

    protected function replaceInterface(string &$stub, string $name): self
    {
        $stub = str_replace(
            ['DummyInterface', '{{ interface }}', '{{interface}}'],
            $this->getInterfaceName($name),
            $stub
        );

        return $this;
    }

    protected function replaceModel(string &$stub, string $name): self
    {
        $stub = str_replace(
            ['DummyModel', '{{ model }}', '{{model}}'],
            $this->getBaseName($name),
            $stub
        );

        return $this;
    }

    protected function getBaseName(?string $name = null): string
    {
        $name ??= $this->qualifyClass($this->getNameInput());

        return Str::of($name)->classBasename()->before('Repository')->__toString();
    }

    protected function getInterfaceName(?string $name = null): string
    {
        return Str::of($this->getBaseName($name))
            ->append('Interface')
            ->__toString();
    }
}
