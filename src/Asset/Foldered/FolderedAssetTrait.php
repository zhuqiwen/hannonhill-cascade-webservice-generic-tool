<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

trait FolderedAssetTrait {


    public function checkInputIntegrity(\stdClass $assetData)
    {
        $this->checkIfSetParentPath($assetData);
        $this->checkIfSetName($assetData);
    }

    public function checkIfSetParentPath(\stdClass $assetData)
    {
        $className = $this->getClassName();

        if(!isset($assetData->parentFolderPath)){
            throw new InputIntegrityException("$className payload: [parentFolderPath] => 'PATH-TO-PARENT' is missing");
        }
    }
}