<?php

namespace Qu1eeeOJ\Deploy\Contracts;

interface DeployerConfigContract
{
    /**
     * Set maintenance mode
     */
    public function needMaintenance(): bool;

    /**
     * Get secret
     */
    public function getSecret(): ?string;

    /**
     * Get Deployer Instructions Path
     */
    public function getDeployerInstructionsPath(): string;

    /**
     * Get deploy config
     */
    public function getConfig(): array;

    /**
     * Get driver deploy config
     */
    public function getDriverConfig(): array;

    /**
     * Get database table name
     */
    public function getDatabaseTableName(): string;
}
