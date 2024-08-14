<?php

namespace Fatihirday\RepositoryMake\Console\Commands;

use Illuminate\Console\GeneratorCommand;
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
        $name = Str::of($this->qualifyClass($this->getNameInput()))
            ->classBasename()->ucsplit()->first() . 'Interface';

        Artisan::call('make:interface ' . $name );

        return parent::handle();
    }

    protected function getStub()
    {
        return __DIR__ . '/../../stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services\Repositories';
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
            Str::of($name)->classBasename()->ucsplit()->first() . 'Interface',
            $stub
        );

        return $this;
    }

    protected function replaceModel(string &$stub, string $name): self
    {
        $stub = str_replace(
            ['DummyModel', '{{ model }}', '{{model}}'],
            Str::of($name)->classBasename()->ucsplit()->first(),
            $stub
        );

        return $this;
    }
}
