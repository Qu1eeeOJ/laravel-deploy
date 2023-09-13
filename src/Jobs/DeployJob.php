<?php

namespace Qu1eeeOJ\Deploy\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class DeployJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * DeployJob constructor
     */
    public function __construct(private readonly string $uniqueId = '') {}

    /**
     * The unique ID of the job.
     */
    public function uniqueId(): string
    {
        return sprintf('deployer.%s', $this->uniqueId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call('deployer:run');
    }
}
