<?php

namespace Qu1eeeOJ\Deploy\Services;

use Qu1eeeOJ\Deploy\Contracts\DeployerConfigContract;

readonly class DeployConfigService implements DeployerConfigContract
{
    /**
     * @var array
     */
    private array $config;

    /**
     * DeployConfigService constructor
     */
    public function __construct()
    {
        $this->config = config('deploy');
    }

    /**
     * @return bool
     */
    public function needMaintenance(): bool
    {
        return $this->config['maintenance_mode'];
    }

    /**
     * @return string|null
     */
    public function getSecret(): ?string
    {
        return $this->config['secret'];
    }

    /**
     * @return string
     */
    public function getDeployerInstructionsPath(): string
    {
        return $this->config['instructions_path'];
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return array
     */
    public function getDriverConfig(): array
    {
        return $this->config['drivers'][$this->config['deployer']];
    }

    /**
     * @return string
     */
    public function getDatabaseTableName(): string
    {
        return $this->config['table'];
    }
}
