<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;
use SebastianBergmann\CodeCoverage\Report\PHP;

class ContaineredAsset extends Asset{

    public function getNewAssetPath()
    {
        $path = DIRECTORY_SEPARATOR
            . trim($this->newAsset->parentContainerPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . trim($this->newAsset->name);

        return $path;
    }


    public function createAsset()
    {
        
    }

    public function createParent()
    {

    }

}