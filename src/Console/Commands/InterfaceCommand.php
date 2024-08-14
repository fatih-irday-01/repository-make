<?php

namespace Fatihirday\RepositoryMake\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class InterfaceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {name}';

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
    protected $type = 'Interface';


    protected function getStub()
    {
        return __DIR__ . '/../../stubs/interface.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\'.config('repository.folder', 'Services').'\Interfaces';
    }
}
