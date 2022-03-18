<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;
use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;

class Folder extends Asset {
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

}