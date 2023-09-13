<?php

namespace Qu1eeeOJ\Deploy\Contracts;

interface DeployerInstructionsRepositoryContract
{
    /**
     * Get ran instructions.
     */
    public function getRan(): array;

    /**
     * Log that an instruction was run.
     */
    public function setRan(string $file): void;
}
