<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class FormatXSLT extends Format {
    protected $assetTypeDisplay = "XSLT Format";
    protected $assetTypeCreate = ASSET_FORMAT_XSLT_CREATE;
    protected $assetTypeFetch = ASSET_FORMAT_XSLT_FETCH;


    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetScript();
    }

    public function checkIfSetScript()
    {
        if(!isset($this->newAsset->xml) || empty(trim($this->newAsset->xml))){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [xml] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }
    }
}