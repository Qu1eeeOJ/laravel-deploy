<?php

namespace Qu1eeeOJ\Deploy\Commands;

use Illuminate\Console\Command;
use Qu1eeeOJ\Deploy\Services\DeployService;

class DeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deployer:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get changes from source';

    /**
     * DeployCommand constructor
     */
    public function __construct(private readonly DeployService $deployService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->deployService->run();

        return static::SUCCESS;
    }
}
