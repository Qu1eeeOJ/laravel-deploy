<?php

namespace Qu1eeeOJ\Deploy\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeDeployerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:deployer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new deployer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Deployer';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/deployer.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Deployers';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [];
    }
}
