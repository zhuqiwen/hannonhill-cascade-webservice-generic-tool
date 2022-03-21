<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class FormatScript extends Format {
    public $assetTypeDisplay = "Velocity Format";
    public $assetTypeCreate = ASSET_FORMAT_SCRIPT_CREATE;
    public $assetTypeFetch = ASSET_FORMAT_SCRIPT_FETCH;


    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetScript();
    }

    public function checkIfSetScript()
    {
        if(!isset($this->newAsset->script) || empty(trim($this->newAsset->script))){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [script] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }
    }
}