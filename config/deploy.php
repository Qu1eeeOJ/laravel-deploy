<?php

return [
    /**
     * [Current deployer]
     *
     * What driver use for deploy.
     */
    'deployer' => \Qu1eeeOJ\Deploy\Deployers\GitHubDeployer::class,

    /**
     * [Queue]
     *
     * Perform a task in a queue.
     */
    'queue' => true,

    /**
     * [Secret key for service]
     *
     * The key you specified in the pushing client.
     */
    'secret' => env('DEPLOY_SECRET'),

    /**
     * [Drivers]
     *
     * Settings for deployers.
     */
    'drivers' => [
        \Qu1eeeOJ\Deploy\Deployers\GitHubDeployer::class => [
            'type' => 'hmac',
            'header' => 'X-Hub-Signature',

            'git_path' => env('DEPLOY_GIT_PATH', 'git'),
            'git_remote' => env('DEPLOY_GIT_REMOTE')
        ]
    ],

    /**
     * [Path to instructions]
     *
     * The path to the deployment instructions.
     */
    'instructions_path' => base_path(env('DEPLOY_INSTRUCTIONS_PATH', '/deploys')),

    /**
     * [Database]
     *
     * The name of the table in the database with the instructions carried out.
     */
    'table' => 'deployers',

    /**
     * [Maintenance mode]
     *
     * Should the site be put into maintenance mode during the update.
     */
    'maintenance_mode' => (bool) env('DEPLOY_MAINTENANCE_MODE', true),

    /**
     * [Logs]
     *
     * Logging Settings.
     */
    'logging' => storage_path('logs/deploy/deploy.log')
];
