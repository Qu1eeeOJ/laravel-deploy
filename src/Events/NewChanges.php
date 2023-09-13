<?php

namespace Qu1eeeOJ\Deploy\Events;

use Illuminate\Queue\SerializesModels;

class NewChanges
{
    use SerializesModels;

    /**
     * DeployFailed constructor
     */
    public function __construct(public $changes = []) {}
}
