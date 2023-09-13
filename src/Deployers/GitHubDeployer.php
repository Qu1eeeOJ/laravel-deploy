<?php

namespace Qu1eeeOJ\Deploy\Deployers;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Qu1eeeOJ\Deploy\Contracts\DeployerConfigContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerConsoleContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerContract;
use Qu1eeeOJ\Deploy\Traits\Dispatchable;

final readonly class GitHubDeployer implements DeployerContract
{
    use Dispatchable;

    /**
     * Git path for execute command
     *
     * @var string
     */
    private string $git;

    /**
     * @var string
     */
    private string $loggerFile;

    /**
     * GitHubDeployer constructor
     */
    public function __construct(
        private DeployerConsoleContract $console,
        private DeployerConfigContract  $config
    )
    {
        $this->git = $this->config->getDriverConfig()['git_path'];

        $this->loggerFile =
            str_replace(
                'deploy.log',
                sprintf('deploy-%s.log', Carbon::now()->format('Y-m-d_h-i-s')),
                $this->config->getConfig()['logging']
            );
    }

    /**
     * Pull changes from GitHub
     */
    public function deploy(): array
    {
        $this->console->info(sprintf('[%s] Deploy started...', self::class));

        // ---------------------------------------
        // 1. We reset the uncommitted changes
        // 2. We get new changes and write them to a file
        // ---------------------------------------
        $this->console->info(exec(sprintf('%s -C %s reset --hard', $this->git, base_path())));
        $this->console->info(exec(sprintf('%s -C %s pull > %s', $this->git, base_path(), $this->loggerFile)));
        // ---------------------------------------

        $this->console->info(sprintf('[%s] Deploy finish...', self::class));

        return [];
    }

    /**
     * Validate request
     */
    public function isValidRequest(?Request $request = null): bool
    {
        if (is_null($request)) {
            $request = request();
        }

        // Get data from GitHub hook
        if (empty($data = json_encode($request->getContent(), true))) {
            return false;
        }

        // If there is an empty secret in the config, then we skip the request further
        if (is_null($secret = $this->config->getSecret())) {
            return true;
        }

        $headerValue = $request->header($this->config->getDriverConfig()['header']);
        // Check valid header
        if (! $headerValue) {
            return false;
        }

        return hash_equals('sha=1', hash_hmac('sha1', $request->getContent(), $secret));
    }
}
