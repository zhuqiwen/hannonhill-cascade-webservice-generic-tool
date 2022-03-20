<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class AssetFactory extends AssetFactoryContainer {
    public $assetTypeDisplay = "Asset Factory";
    public $assetTypeFetch = ASSET_ASSET_FACTORY_FETCH;
    public $assetTypeCreate = ASSET_ASSET_FACTORY_CREATE;


    public function checkIfSetAssetType(\stdClass $assetData)
    {
        if(!isset($assetData->assetType) || empty(trim($assetData->assetType))){
            $msg = $this->getClassName();
            $msg .= " payload:";
            $msg .= " [assetType] is missing or has empty value. Supported values: 'page', 'file', 'folder', 'format', 'symlink', 'template', and 'block'";
            throw new InputIntegrityException($msg);
        }
    }
}