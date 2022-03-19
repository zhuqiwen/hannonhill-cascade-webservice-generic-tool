<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

trait FolderedAssetTrait {





    public function checkInputIntegrity()
    {
        $this->checkIfSetParentPath();
        $this->checkIfSetName();
    }

    public function checkIfSetParentPath()
    {
        $className = $this->getClassName();

        if(!isset($this->newAsset->parentFolderPath)){
            throw new InputIntegrityException("$className payload: [parentFolderPath] => 'PATH-TO-PARENT' is missing");
        }
    }
}