<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class Symlink extends Folder {
    public $assetTypeDisplay = "Symlink";
    public $assetTypeFetch = ASSET_SYMLINK_FETCH;
    public $assetTypeCreate = ASSET_SYMLINK_CREATE;


    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetLinkURL($assetData);
    }

    public function checkIfSetLinkURL(\stdClass $asset)
    {
        $isURL = filter_var($asset->linkURL, FILTER_VALIDATE_URL);

        if(!isset($asset->linkURL) || empty(trim($asset->linkURL))){
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