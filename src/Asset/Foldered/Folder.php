<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSet;
use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class Folder extends Asset {

    use FolderedAssetTrait;

    protected $assetTypeDisplay = "Folder";
    protected $assetTypeFetch = ASSET_FOLDER_FETCH;
    protected $assetTypeCreate = ASSET_FOLDER_CREATE;


    public function createAsset(): Asset
    {
        try {
            new Folder($this->wcms, $this->newAsset->parentFolderPath);
        }catch (AssetNotFoundException $e){
            $msg = $e->getMessage() . ", which will be created now." . PHP_EOL;
            $this->echoForCLI($msg);
            $this->createParent();
        }catch (\RuntimeException $e){
            $this->echoForCLI($e->getMessage());
        }

        if(!$this->assetExists($this->getNewAssetPath()))
        {
            unset($this->newAsset->path);
            $result = $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);
            $msg = "The following Asset has been created:" . PHP_EOL;
            $msg .= "\tAsset Type: " . $this->assetTypeDisplay . PHP_EOL;
            $msg .= "\tAsset Path: " . $this->getNewAssetPath() . PHP_EOL;
            $this->echoForCLI($msg);
            $this->newAsset->id = $result->createReturn->createdAssetId;
        }
        else
        {
            //if oldAsset is set, nothing new created
            //if not, a new asset is created
            $this->setOldAsset($this->getNewAssetPath());
        }

        return $this;
    }

    public function createParent()
    {
        $asset = $this->getGrantParentAssetForCreate();
        $path = $asset->parentFolderPath . DIRECTORY_SEPARATOR . $asset->name;
        $path = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);

        if ($path == DIRECTORY_SEPARATOR)
        {
            return;
        }

        if(!$this->wcms->assetExists($path, $this->assetTypeFetch))
        {
            $folder = new Folder($this->wcms);
            $folder->setNewAsset($asset);
            $folder->createAsset();
            //set parent container just created
            $assetInfo = (object)[
                'class' => get_class($this),
                'data' => $asset
            ];
            $folder->putContainerCreated($assetInfo);
            //pass parent container info to child
            $this->setContainersCreatedOnTheWay(
                array_merge(
                    $this->getContainersCreatedOnTheWay(),
                    $folder->getContainersCreatedOnTheWay()
                )
            );
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


    public function checkDependencies()
    {
        parent::checkDependencies();
        if(isset($this->newAsset->metadataSetPath) && !empty(trim($this->newAsset->metadataSetPath))){
            $this->checkExistenceMetadataSet($this->newAsset->metadataSetPath);
        }
    }

    public function checkExistenceMetadataSet(string $path)
    {
        $asset = new MetadataSet($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }

    public function checkIfSetXHTMLOrDataDefinition()
    {
        $hasXhtml = isset($this->newAsset->xhtml) && !empty(trim($this->newAsset->xhtml));
        $hasStructuredData = isset($this->newAsset->structuredData->definitionPath) && !empty($this->newAsset->structuredData->definitionPath);

        if(!$hasXhtml && !$hasStructuredData){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [structuredData][definitionPath] or [xhtml] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }
    }
}