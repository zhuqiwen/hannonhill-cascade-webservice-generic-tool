<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class Template extends Folder {
    public $assetTypeDisplay = "Template";
    public $assetTypeFetch = ASSET_TEMPLATE_FETCH;
    public $assetTypeCreate = ASSET_TEMPLATE_CREATE;


    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetXML($assetData);
    }



}
