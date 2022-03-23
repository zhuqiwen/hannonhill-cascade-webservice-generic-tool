<?php

namespace Edu\IU\Framework\GenericUpdater\Action;


class Action
{
    public $steps = [];
    public $appliedSteps = [];


    public function __construct(array $assets, string $action)
    {
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

                $results[] = $step->apply();
                array_unshift($this->appliedSteps, $step);
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
