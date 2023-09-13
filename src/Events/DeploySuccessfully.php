<?php

namespace Qu1eeeOJ\Deploy\Events;

use Illuminate\Queue\SerializesModels;

class DeploySuccessfully
{
    use SerializesModels;

    /**
     * DeploySuccessfully constructor
     */
    public function __construct(public $commits = null) {}
}
