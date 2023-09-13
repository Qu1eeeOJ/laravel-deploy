<?php

namespace Qu1eeeOJ\Deploy\Services\Console;

use Qu1eeeOJ\Deploy\Contracts\DeployerConsoleContract;
use Symfony\Component\Console\Output\ConsoleOutput;

final readonly class LaravelConsole implements DeployerConsoleContract
{
    /**
     * LaravelConsole constructor
     */
    public function __construct(private ConsoleOutput $console) {}

    /**
     * @param string $message
     * @return void
     */
    public function info(string $message = ''): void
    {
        $this->console->write(sprintf('<info>%s</info>', $message), true);
    }

    /**
     * @param string $message
     * @return void
     */
    public function error(string $message = ''): void
    {
        $this->console->write(sprintf('<error>%s</error>', $message), true);
    }
}
