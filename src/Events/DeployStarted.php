<?php

namespace Qu1eeeOJ\Deploy\Events;

use Illuminate\Queue\SerializesModels;

class DeployStarted
{
    use SerializesModels;

    /**
     * DeployStarted constructor
     */
    public function __construct(public $commits = null) {}
}
