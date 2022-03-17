<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

class Folder extends FolderContainedAsset implements AssetInterface
{
    public $assetTypeDisplay = "Folder";
    public $assetTypeFetch = ASSET_FOLDER_FETCH;
    public $assetTypeCreate = ASSET_FOLDER_CREATE;


    public function updateContent()
    {
    }


    public function createParent()
    {
        $data = $this->prepareParentAssetForCreate();
        $path = $data['path'];
        $grantParentPath = $data['grantParentPath'];
        $parentAsset = $data['parentAsset'];


        if($path == DIRECTORY_SEPARATOR)
        {
            return;
        }

        if(!$this->wcms->assetExists($grantParentPath, $this->assetTypeFetch))
        {
            $folder = new Folder($this->wcms);
            $folder->setNewAsset($parentAsset);
            $folder->createParent();
        }

        // when create folder, path should not be included in payload
        unset($parentAsset->path);
        $this->wcms->createAsset($this->assetTypeCreate, $parentAsset);

    }




}