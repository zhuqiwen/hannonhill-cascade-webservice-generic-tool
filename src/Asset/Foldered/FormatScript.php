<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class FormatScript extends Folder {
    public $assetTypeDisplay = "Velocity Format";
    public $assetTypeCreate = ASSET_FORMAT_SCRIPT_CREATE;
    public $assetTypeFetch = ASSET_FORMAT_SCRIPT_FETCH;


    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetScript($assetData);
    }

    public function checkIfSetScript(\stdClass $asset)
    {
        if(!isset($asset->script) || empty(trim($asset->script))){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [script] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }
    }
}