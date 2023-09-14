<?php

namespace Qu1eeeOJ\Deploy\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeploySuccessfully
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * DeploySuccessfully constructor
     */
    public function __construct(public $commits = null) {}
}
