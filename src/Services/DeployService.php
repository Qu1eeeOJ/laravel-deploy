<?php

namespace Qu1eeeOJ\Deploy\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Qu1eeeOJ\Deploy\Contracts\DeployerConfigContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerInstructionsRepositoryContract;
use Qu1eeeOJ\Deploy\Events\DeployFailed;
use Qu1eeeOJ\Deploy\Events\DeployStarted;
use Qu1eeeOJ\Deploy\Events\DeploySuccessfully;

readonly class DeployService
{
    /**
     * DeployService constructor
     */
    public function __construct(
        private DeployerContract        $deployer,
        private DeployerConfigContract  $config
    ) {}

    /**
     * Run deploy
     */
    public function run(): void
    {
        DeployStarted::dispatch();
        $this->setMaintenance(true);

        try {
            $changes = $this->deployer->deploy();
            $this->runInstructions();

            DeploySuccessfully::dispatch($changes);
        } catch (\Throwable $throwable) {
            DeployFailed::dispatch($throwable);
        }

        $this->setMaintenance(false);
    }

    /**
     * Get the name of the file for logs
     */
    public static function getLogger(): \Psr\Log\LoggerInterface
    {
        return Log::build(App::make(DeployerConfigContract::class)->getConfig()['logging']);
    }

    /**
     * Set maintenance mode
     */
    private function setMaintenance(bool $mode): void
    {
        if ($this->config->needMaintenance()) {
            $mode
                ? App::maintenanceMode()->activate([])
                : App::maintenanceMode()->deactivate();
        }
    }

    /**
     * Run instructions
     */
    private function runInstructions(): void
    {
        Artisan::call('deployer:run-instructions');
    }
}
