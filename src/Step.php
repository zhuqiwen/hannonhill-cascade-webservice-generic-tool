<?php

namespace Edu\IU\Framework\GenericUpdater;


use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class Step
{
    public $asset;
    public $action;
    public $parameters;

    public function __construct(Asset $asset, string $action, array $parameters = [])
    {
        $this->asset = $asset;
        $this->action = $action;
        $this->parameters = $parameters;
    }

    public function apply()
    {
        return call_user_func_array([$this->asset, $this->action], $this->parameters);
    }

    public function rollback()
    {
        $rollbackAction = "rollback" . ucfirst($this->action);

        return call_user_func_array([$this->asset, $rollbackAction], $this->parameters);
    }
}