<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;


trait AssetTrait
{

    protected $wcms;
    public $oldAsset;
    public $newAsset;
    public $assetTypeDisplay;
    public $assetTypeFetch;
    public $assetTypeCreate;



    public function setOldAsset(string $path)
    {
        if($this->assetExists($path))
        {
            $asset = $this->wcms->fetchAsset($path, $this->assetTypeFetch);
            $this->oldAsset = $asset->{$this->assetTypeCreate};
        }
        else
        {
            //TODO: set error and throw exception for user api key
        }
    }

    public function setNewAsset(\stdClass $assetData)
    {
        $this->newAsset = $assetData;
    }

    public function assetExists(string $path): bool
    {
        return $this->wcms->assetExists($path, $this->assetTypeFetch);
    }

    public function updateAsset()
    {
        $this->wcms->saveAsset($this->newAsset, $this->assetTypeCreate);
    }

    public function rollbackUpdateAsset()
    {
        $this->wcms->saveAsset($this->oldAsset, $this->assetTypeCreate);
    }


    public function getNewAssetPath()
    {
        return $this->newAsset->path;
    }

    public function getParentPathForCreate()
    {
        return $this->newAsset->parentFolderPath;
    }

    public function createAsset()
    {
    }

    public function deleteAsset()
    {
        if($this->wcms->assetExists($this->oldAsset->path, $this->assetTypeFetch))
        {
            $this->wcms->deleteAsset($this->assetTypeFetch, $this->oldAsset->path);
        }
    }


    public function prepareParentAssetForCreate(): array
    {
        $path = $this->getNewAssetPath();
        $pathArray = explode(DIRECTORY_SEPARATOR, $path);
        // remove current folder name
        array_pop($pathArray);
        $parentName = array_pop($pathArray);
        $grantParentPath = implode(DIRECTORY_SEPARATOR, $pathArray);
        $grantParentPath = empty($grantParentPath) ? DIRECTORY_SEPARATOR : $grantParentPath;



        $parentContainerKey = strpos($this->containerClassName, 'Folder') ? 'parentFolderPath' : 'parentContainerPath';

        $parentAsset = (object) [
            $parentContainerKey => $grantParentPath,
            'name' => $parentName,
            'path' => str_replace("//", "/", $grantParentPath . "/" . $parentName),
        ];

        return compact("path", "grantParentPath", "parentAsset");

    }

}