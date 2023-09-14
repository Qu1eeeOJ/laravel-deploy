<?php

namespace Qu1eeeOJ\Deploy\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChanges
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * DeployFailed constructor
     */
    public function __construct(public $changes = []) {}
}
