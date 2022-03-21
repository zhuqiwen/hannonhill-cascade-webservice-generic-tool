<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class AssetFactory extends AssetFactoryContainer {
    public $assetTypeDisplay = "Asset Factory";
    public $assetTypeFetch = ASSET_ASSET_FACTORY_FETCH;
    public $assetTypeCreate = ASSET_ASSET_FACTORY_CREATE;


    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetAssetType();
    }

    public function checkIfSetAssetType()
    {
        if(!isset($this->newAsset->assetType) || empty(trim($this->newAsset->assetType))){
            $msg = $this->getClassName();
            $msg .= " payload:";
            $msg .= " [assetType] is missing or has empty value. Supported values: 'page', 'file', 'folder', 'format', 'symlink', 'template', and 'block'";
            throw new InputIntegrityException($msg);
        }
    }

    public function checkDependencies()
    {
        if (isset($this->newAsset->baseAssetPath) && !empty(trim($this->newAsset->baseAssetPath))){
            $this->checkExistenceBaseAsset($this->newAsset->baseAssetPath, $this->newAsset->assetType);
        }
    }

    public function checkExistenceBaseAsset(string $path, string $assetType)
    {
        $childClasses = $this->getChildClassesOf("Edu\IU\Framework\GenericUpdater\Asset\Foldered\Folder");
        $childClass = $childClasses[$assetType];

        $asset = new $childClass($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }


}