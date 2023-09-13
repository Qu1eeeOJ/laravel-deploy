<?php

namespace Qu1eeeOJ\Deploy\Contracts;

interface DeployerConsoleContract
{
    /**
     * Show info message
     */
    public function info(string $message = ''): void;

    /**
     * Show error message
     */
    public function error(string $message = ''): void;
}
