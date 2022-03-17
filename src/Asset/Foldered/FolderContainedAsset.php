<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

class FolderContainedAsset extends Asset {
    public $containerClassName = 'Folder';

    public function getParentPathForCreate()
    {
        return $this->newAsset->parentFolderPath;
    }


    public function createAsset()
    {
        if(!$this->wcms->assetExists($this->getParentPathForCreate(), $this->assetTypeFetch))
        {
            $this->createParent();
        }

        unset($this->newAsset->path);
        $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);

    }

    public function createParent()
    {
        $data = $this->prepareParentAssetForCreate();
        $parentAsset = $data['parentAsset'];


        if($parentAsset->path != DIRECTORY_SEPARATOR)
        {
            $containerClassName = $this->getNamespace() . '\\' . $this->containerClassName;
            $folder = new $containerClassName($this->wcms);
            $folder->setNewAsset($parentAsset);

            $folder->createAsset();
        }
    }

}