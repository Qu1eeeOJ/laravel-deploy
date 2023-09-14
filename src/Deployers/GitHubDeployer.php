<?php

namespace Qu1eeeOJ\Deploy\Deployers;

use Illuminate\Http\Request;
use Qu1eeeOJ\Deploy\Contracts\DeployerConfigContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerConsoleContract;
use Qu1eeeOJ\Deploy\Contracts\DeployerContract;
use Qu1eeeOJ\Deploy\Traits\Dispatchable;
use Qu1eeeOJ\Deploy\Services\DeployService;

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
     * Logger
     * 
     * @var \Psr\Log\LoggerInterface
     */
    private \Psr\Log\LoggerInterface $logger;

    /**
     * GitHubDeployer constructor
     */
    public function __construct(
        private DeployerConsoleContract $console,
        private DeployerConfigContract  $config
    )
    {
        $this->git = $this->config->getDriverConfig()['git_path'];
        $this->logger = DeployService::getLogger();
    }

    /**
     * Pull changes from GitHub
     */
    public function deploy(): array
    {
        $message = sprintf('[%s] Deploy started...', self::class);
        $this->logger->info($message);
        $this->console->info($message);

        // ---------------------------------------
        // 1. We reset the uncommitted changes
        // 2. We get new changes and write them to a file
        // ---------------------------------------
        $message = exec(sprintf('%s -C %s reset --hard', $this->git));
        $this->logger->info($message);
        $this->console->info($message);

        $message = exec(sprintf('%s -C %s pull', $this->git, base_path()));
        $this->logger->info($message);
        $this->console->info($message);
        // ---------------------------------------

        $message = sprintf('[%s] Deploy finish...', self::class);
        $this->logger->info($message);
        $this->console->info($message);

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

        return hash_equals(
            'sha1=' . hash_hmac('sha1', $request->getContent(), $secret),
            $headerValue
        );
    }
}
