<?php

if (! function_exists('deployer')) {
    /**
     * @return \Qu1eeeOJ\Deploy\DeployService
     */
    function deployer(): \Qu1eeeOJ\Deploy\DeployService
    {
        return new \Qu1eeeOJ\Deploy\DeployService();
    }
}
