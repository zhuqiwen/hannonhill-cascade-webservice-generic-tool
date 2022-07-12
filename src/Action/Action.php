<?php

namespace Edu\IU\Framework\GenericUpdater\Action;


class Action
{
    public $steps = [];
    public $appliedSteps = [];
    public $mode;
    public $action;


    public function __construct(array $assets, string $action, $mode = null)
    {
        $this->mode = $mode;
        $this->action = $action;
        try{
            foreach ($assets as $asset) {
                $this->steps = array_merge($this->steps, $asset->getSteps($action));
            }
        }catch (\RuntimeException $e){

            $errorFiles = $e->getTrace();
            $errorFile = array_pop($errorFiles);
            $msg = "In " . $errorFile['file'];
            $msg .= " on line " . $errorFile['line'] . ": " . PHP_EOL;
            $msg .= $e->getMessage() . PHP_EOL;
            $msg .= "Task aborted." . PHP_EOL;
            die($msg);
        }

    }


    public function apply()
    {
        try
        {
            $results = [];
            foreach ($this->steps as $step)
            {

                $result = $step->apply();
                array_unshift($this->appliedSteps, $step);
                if($this->mode == 'display progress'){
                    echo "event: in-progress\n";
                    echo "data: " . $result->getSiteName() . ': ' . $this->action . ': ' . $result->getOldAsset()->path .  " successfully.\n\n";
                    ob_end_flush();
                    flush();
                    usleep(500000);
                }

                $results[] = $result;
            }

            return $results;
        }
        catch (\RuntimeException $e)
        {
            return $this->rollback($e->getMessage());
        }

    }

    public function rollback(string $errorMessage): array
    {
        $results = [];
        foreach($this->appliedSteps as $step)
        {
            $results[] = $step->rollback();
        }

        $results['message'] = 'Rollback has been triggered because of: ' . PHP_EOL . $errorMessage;
        return $results;
    }
}
