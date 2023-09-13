<?php

namespace Qu1eeeOJ\Deploy\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Qu1eeeOJ\Deploy\Contracts\DeployerConfigContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerInstructionsRepositoryContract;

class RunDeployInstructionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deployer:run-instructions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run instructions';

    /**
     * DeployCommand constructor
     */
    public function __construct(
        private readonly FileSystem $files,
        private readonly DeployerConfigContract $config,
        private readonly DeployerInstructionsRepositoryContract $repository
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Grab all instructions files
        $files = $this->getInstructionsFiles();

        $this->requireFiles($instructions = $this->pendingInstructions(
            $files, $this->getRanInstructions()
        ));

        // As soon as we have the incomplete deployment instructions,
        // we will be able to run them and write them to the database.
        $this->runPending($instructions);

        return static::SUCCESS;
    }

    /**
     * Get instructions files.
     */
    protected function getInstructionsFiles(): array
    {
        return $this->files->glob($this->config->getDeployerInstructionsPath() . '/*.php');
    }

    /**
     * Require once instructions files.
     */
    protected function requireFiles(array $files): void
    {
        foreach ($files as $file) {
            $this->files->requireOnce($file);
        }
    }

    /**
     * Get the instructions files that have not yet run.
     */
    protected function pendingInstructions(array $files, array $ran): array
    {
        return Collection::make($files)
            ->reject(function ($file) use ($ran) {
                return in_array($this->getInstructionName($file), $ran);
            })->values()->all();
    }

    /**
     * Get the instruction file name.
     */
    protected function getInstructionName(string $file): string
    {
        $explodedFile = explode(DIRECTORY_SEPARATOR, str_replace('.php', '', $file));

        return array_pop($explodedFile);
    }

    /**
     * Get ran instructions.
     */
    protected function getRanInstructions(): array
    {
        return $this->repository->getRan();
    }

    /**
     * Run an array of instructions.
     */
    protected function runPending(array $instructions): void
    {
        // First you need to make sure there is something to run
        if (count($instructions) === 0) {
            $this->info('Nothing to run');

            return;
        }

        $rows = [];
        $this->info('Running instructions with progress bar');
        $this->withProgressBar($instructions, function (string $instruction) use (&$rows) {
            $name = $this->getInstructionName($instruction);

            try {
                $this->runInstruction($instruction);

                $rows[] = [$name, 'DONE'];
            } catch (\Throwable) {
                $rows[] = [$name, 'FAILED'];
            }
        });

        $this->newLine(2);
        $this->table(['Name', 'Status'], $rows);
    }

    /**
     * Resolve an instruction instance from a deployers path
     */
    protected function resolvePath(string $path): object
    {
        $class = $this->getInstructionClass($this->getInstructionName($path));

        if (class_exists($class) && realpath($path) == (new \ReflectionClass($class))->getFileName()) {
            return new $class;
        }

        $instruction = $this->files->getRequire($path);

        if (is_object($instruction)) {
            return method_exists($instruction, '__construct')
                ? $this->files->getRequire($path)
                : clone $instruction;
        }

        return new $class;
    }

    /**
     * Get the name of the instruction.
     */
    protected function getInstructionClass(string $instructionName): string
    {
        return Str::studly(implode('_', array_slice(explode('_', $instructionName), 4)));
    }

    /**
     * Run instruction.
     */
    protected function runInstruction(string $path): void
    {
        $this->resolvePath($path)->up();

        // Save to database information
        $this->repository->setRan($this->getInstructionName($path));
    }
}
