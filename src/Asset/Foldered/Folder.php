<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSet;
use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class Folder extends Asset {

    use FolderedAssetTrait;

    public $assetTypeDisplay = "Folder";
    public $assetTypeFetch = ASSET_FOLDER_FETCH;
    public $assetTypeCreate = ASSET_FOLDER_CREATE;


    public function createAsset()
    {
        try {
            new Folder($this->wcms, $this->newAsset->parentFolderPath);
        }catch (AssetNotFoundException $e){
            echo $e->getMessage() . ", which will be created now." . PHP_EOL;
            $this->createParent();
        }catch (\RuntimeException $e){
            echo $e->getMessage();
        }

        if(!$this->assetExists($this->getNewAssetPath()))
        {
            unset($this->newAsset->path);
            $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);
            echo "The following Asset has been created:" . PHP_EOL;
            echo "\tAsset Type: " . $this->assetTypeDisplay . PHP_EOL;
            echo "\tAsset Path: " . $this->getNewAssetPath() . PHP_EOL;
        }
    }

    public function createParent()
    {
        $asset = $this->getGrantParentAssetForCreate();
        $path = $asset->parentFolderPath . DIRECTORY_SEPARATOR . $asset->name;

        if ($path == DIRECTORY_SEPARATOR)
        {
            return;
        }

        if(!$this->wcms->assetExists($path, $this->assetTypeFetch))
        {
            $folder = new Folder($this->wcms);
            $folder->setNewAsset($asset);
            $folder->createAsset();
        }


    }

    public function getGrantParentAssetForCreate(): \stdClass
    {
        $array = explode(DIRECTORY_SEPARATOR, $this->newAsset->parentFolderPath);
        $grantParentName = array_pop($array);
        $grantParentPath = implode(DIRECTORY_SEPARATOR, $array);

        return (object)[
            'parentFolderPath' => empty($grantParentPath) ? '/' : $grantParentPath,
            'name' => $grantParentName
        ];

    }


    public function checkDependencies(\stdClass $assetData)
    {
        parent::checkDependencies($assetData);
        if(isset($assetData->metadataSetPath) && !empty(trim($assetData->metadataSetPath))){
            $this->checkExistenceMetadataSet();
        }
    }

    public function checkExistenceMetadataSet(string $path)
    {
        $asset = new MetadataSet($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }

    public function checkIfSetXHTMLOrDataDefinition(\stdClass $asset)
    {
        $hasXhtmlOrStructuredData = isset($asset->xhtml) && empty(trim($asset->xhtml));
        $hasXhtmlOrStructuredData = $hasXhtmlOrStructuredData
            ||
            (isset($asset->structuredData->definitionPath) && !empty($asset->structuredData->definitionPath));

        if(!$hasXhtmlOrStructuredData){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [structuredData][definitionPath] or [xhtml] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }
    }
}