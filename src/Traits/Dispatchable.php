<?php

namespace Qu1eeeOJ\Deploy\Traits;

use Qu1eeeOJ\Deploy\DeployService;

trait Dispatchable
{
    /**
     * @see \Qu1eeeOJ\Deploy\DeployService::pull()
     */
    public function dispatchSync(string $uniqueId = ''): mixed
    {
        return DeployService::pull($uniqueId);
    }

    /**
     * @see \Qu1eeeOJ\Deploy\DeployService::queue()
     */
    public function dispatch(string $uniqueId = ''): \Illuminate\Foundation\Bus\PendingDispatch
    {
        return DeployService::queue($uniqueId);
    }
}
