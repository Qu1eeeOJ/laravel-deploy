<?php

namespace Qu1eeeOJ\Deploy\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Qu1eeeOJ\Deploy\Contracts\DeployerConfigContract;

class MakeDeployerInstructionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:deployer-instructions {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new deployer-instructions class';

    /**
     * MakeDeployerInstructionsCommand constructor
     */
    public function __construct(
        protected readonly FileSystem $files,
        protected readonly DeployerConfigContract $config
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->existsInstructionClass($name = $this->argument('name'));

        $stub = $this->getStub();
        $path = $this->getPath($name);

        $this->files->ensureDirectoryExists(dirname($path));

        $this->files->put($path, $stub);

        return static::SUCCESS;
    }

    /**
     * Ensure that an instructions with the given doesn't already exist.
     *
     * @throws \InvalidArgumentException
     */
    protected function existsInstructionClass(string $name): void
    {
        $instructionsFiles = $this->files->glob($this->config->getDeployerInstructionsPath().'/*.php');

        foreach ($instructionsFiles as $instructionsFile) {
            $this->files->requireOnce($instructionsFile);
        }

        if (class_exists($className = $this->getClassName($name))) {
            throw new \InvalidArgumentException("A {$className} class already exists.");
        }
    }

    /**
     * Get the class name of an instructions name.
     */
    protected function getClassName(string $name): string
    {
        return Str::studly($name);
    }

    /**
     * Get the full path to the instructions.
     */
    protected function getPath(string $name): string
    {
        return sprintf(
            '%s/%s_%s.php',
            $this->config->getDeployerInstructionsPath(),
            $this->getDateTimePrefix(),
            $name
        );
    }

    /**
     * Get the date prefix for the instructions.
     */
    protected function getDateTimePrefix(): string
    {
        return date('Y_m_d_His');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->files->get(__DIR__ . '/stubs/deployer.instructions.stub');
    }
}
