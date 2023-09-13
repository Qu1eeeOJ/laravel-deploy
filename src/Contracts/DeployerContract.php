<?php

namespace Qu1eeeOJ\Deploy\Contracts;

interface DeployerContract
{
    /**
     * Pull changes
     */
    public function deploy(): mixed;

    /**
     * Validate request
     */
    public function isValidRequest(): bool;
}
