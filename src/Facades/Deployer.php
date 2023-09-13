<?php

namespace Qu1eeeOJ\Deploy\Facades;

use Illuminate\Support\Facades\Facade;
use Qu1eeeOJ\Deploy\DeployService;

/**
 * @see \Qu1eeeOJ\Deploy\DeployService
 *
 * @method static mixed pull(string $uniqueId = '')
 * @method static \Illuminate\Foundation\Bus\PendingDispatch queue(string $uniqueId = '')
 */
class Deployer extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return DeployService::class;
    }
}
