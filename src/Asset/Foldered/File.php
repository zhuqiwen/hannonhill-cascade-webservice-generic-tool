<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class File extends Folder {
    protected $assetTypeDisplay = "File";
    protected $assetTypeFetch = ASSET_FILE_FETCH;
    protected $assetTypeCreate = ASSET_FILE_CREATE;


    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetTextOrData();
    }

    public function checkIfSetTextOrData()
    {
        $hasText = isset($this->newAsset->text) && empty(trim($this->newAsset->text));
        $hasData = isset($this->newAsset->data) && !empty($this->newAsset->data);

        if(!$hasText && !$hasData){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [data] or [text] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }
    }
}