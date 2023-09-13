<?php

namespace Qu1eeeOJ\Deploy\Services\Console;

use Qu1eeeOJ\Deploy\Contracts\DeployerConsoleContract;

final readonly class NullConsole implements DeployerConsoleContract
{
    /**
     * @param string $message
     * @return void
     */
    public function info(string $message = ''): void
    {
        // TODO: Implement info() method.
    }

    /**
     * @param string $message
     * @return void
     */
    public function error(string $message = ''): void
    {
        // TODO: Implement error() method.
    }
}
