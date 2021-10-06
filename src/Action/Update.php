<?php

namespace Edu\IU\Framework\GenericUpdater\Action;


class Update
{
    public $steps = [];
    public $appliedSteps = [];


    public function __construct(array $assets)
    {
        foreach ($assets as $asset)
        {
            $this->steps = array_merge($this->steps, $asset->getUpdateSteps());
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
