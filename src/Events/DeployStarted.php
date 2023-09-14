<?php

namespace Qu1eeeOJ\Deploy\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeployStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * DeployStarted constructor
     */
    public function __construct(public $commits = null) {}
}
