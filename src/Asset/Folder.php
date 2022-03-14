<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

class Folder extends Asset implements AssetInterface
{
    public $assetTypeDisplay = "Folder";
    public $assetTypeFetch = ASSET_FOLDER_FETCH;
    public $assetTypeCreate = ASSET_FOLDER_CREATE;


    public function updateContent()
    {
    }

    public function createAsset()
    {
        if(!$this->wcms->assetExists($this->getParentPath(), $this->assetTypeFetch))
        {
            $this->createParent();
        }

        unset($this->newAsset->path);
        $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);

    }


    public function getParentPath()
    {
        return $this->newAsset->parentFolderPath;
    }

    public function createParent()
    {
        $path = $this->getNewAssetPath();
        $pathArray = explode(DIRECTORY_SEPARATOR, $path);
        // remove current folder name
        array_pop($pathArray);
        $parentName = array_pop($pathArray);
        $grantParentPath = implode(DIRECTORY_SEPARATOR, $pathArray);
        $grantParentPath = empty($grantParentPath) ? DIRECTORY_SEPARATOR : $grantParentPath;


        $asset = (object) [
            'parentFolderPath' => $grantParentPath,
            'name' => $parentName,
            'path' => str_replace("//", "/", $grantParentPath . "/" . $parentName),
        ];

        if($path == DIRECTORY_SEPARATOR)
        {
            return;
        }

        if(!$this->wcms->assetExists($grantParentPath, $this->assetTypeFetch))
        {
            $folder = new Folder($this->wcms);
            $folder->setNewAsset($asset);
            $folder->createParent();
        }

        // when create folder, path should not be included in payload
        unset($asset->path);
        $this->wcms->createAsset($this->assetTypeCreate, $asset);

    }




}