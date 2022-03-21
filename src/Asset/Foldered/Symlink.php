<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class Symlink extends Folder {
    protected $assetTypeDisplay = "Symlink";
    protected $assetTypeFetch = ASSET_SYMLINK_FETCH;
    protected $assetTypeCreate = ASSET_SYMLINK_CREATE;


    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetLinkURL();
    }

    public function checkIfSetLinkURL()
    {
        $isURL = filter_var($this->newAsset->linkURL, FILTER_VALIDATE_URL);

        if(!isset($this->newAsset->linkURL) || empty(trim($this->newAsset->linkURL))){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [linkURL] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }

        if(!$isURL){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", the value of [linkURL] should be a valid url.";
            throw new InputIntegrityException($msg);
        }
    }
}