<?php

namespace Qu1eeeOJ\Deploy;

use Qu1eeeOJ\Deploy\Jobs\DeployJob;

/**
 * @method mixed pull(string $uniqueId = '')
 * @method \Illuminate\Foundation\Bus\PendingDispatch queue(string $uniqueId = '')
 */
class DeployService
{
    /**
     * Pull changes
     */
    public static function pull(string $uniqueId = ''): mixed
    {
        return DeployJob::dispatchSync($uniqueId);
    }

    /**
     * Perform a task in a queue
     */
    public static function queue(string $uniqueId = ''): \Illuminate\Foundation\Bus\PendingDispatch
    {
        return DeployJob::dispatch($uniqueId);
    }
}
