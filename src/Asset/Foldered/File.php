<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class File extends Folder {
    public $assetTypeDisplay = "File";
    public $assetTypeFetch = ASSET_FILE_FETCH;
    public $assetTypeCreate = ASSET_FILE_CREATE;


    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetTextOrData($assetData);
    }

    public function checkIfSetTextOrData(\stdClass $asset)
    {
        $hasTextOrData = isset($asset->text) && empty(trim($asset->text));
        $hasTextOrData = $hasTextOrData
            ||
            (isset($asset->data) && !empty($asset->data));

        if(!$hasTextOrData){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [data] or [text] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }
    }
}