<?php

namespace Qu1eeeOJ\Deploy\Providers;

use Illuminate\Support\ServiceProvider;
use Qu1eeeOJ\Deploy\Commands\DeployCommand;
use Qu1eeeOJ\Deploy\Commands\MakeDeployerCommand;
use Qu1eeeOJ\Deploy\Commands\MakeDeployerInstructionsCommand;
use Qu1eeeOJ\Deploy\Commands\RunDeployInstructionsCommand;
use Qu1eeeOJ\Deploy\Contracts\DeployerConfigContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerConsoleContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerInstructionsRepositoryContract;
use Qu1eeeOJ\Deploy\Repositories\DeployerInstructionsRepository;
use Qu1eeeOJ\Deploy\Services\Console\LaravelConsole;
use Qu1eeeOJ\Deploy\Services\Console\NullConsole;
use Qu1eeeOJ\Deploy\Services\DeployConfigService;

class DeployServiceProvider extends ServiceProvider
{
    /**
     * Path to package config
     *
     * @var string
     */
    const PACKAGE_CONFIG_PATH = __DIR__ . '/../../config/deploy.php';

    /**
     * Path to project config
     *
     * @var string
     */
    const PROJECT_CONFIG_PATH = 'deploy.php';

    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        DeployerConfigContract::class => DeployConfigService::class,
        DeployerInstructionsRepositoryContract::class => DeployerInstructionsRepository::class
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([self::PACKAGE_CONFIG_PATH => config_path(self::PROJECT_CONFIG_PATH)], 'deploy-config');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerSingletons();
        $this->registerCommands();
    }

    /**
     * Register package config
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(self::PACKAGE_CONFIG_PATH, str_replace('.php', '', self::PROJECT_CONFIG_PATH));
    }

    /**
     * Register package singletons.
     */
    private function registerSingletons(): void
    {
        $this->app->singleton(DeployerConsoleContract::class, function ($app) {
            return $app->make(
                $app->runningInConsole()
                    ? LaravelConsole::class
                    : NullConsole::class
            );
        });

        $this->app->bind(DeployerContract::class, function ($app) {
            return $app->make(config('deploy.deployer'));
        });
    }

    /**
     * Register package commands.
     */
    private function registerCommands(): void
    {
        $this->commands([
            DeployCommand::class,
            MakeDeployerCommand::class,
            MakeDeployerInstructionsCommand::class,
            RunDeployInstructionsCommand::class
        ]);
    }
}
