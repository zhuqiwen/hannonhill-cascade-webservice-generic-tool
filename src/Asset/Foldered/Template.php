<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class Template extends Folder {
    protected $assetTypeDisplay = "Template";
    protected $assetTypeFetch = ASSET_TEMPLATE_FETCH;
    protected $assetTypeCreate = ASSET_TEMPLATE_CREATE;


    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetXML();
    }



}
