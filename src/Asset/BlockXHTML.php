<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class BlockXHTML extends Asset implements AssetInterface {
    public $assetTypeDisplay = "Block";
    public $assetTypeFetch = ASSET_BLOCK_XHTML_FETCH;
    public $assetTypeCreate = ASSET_BLOCK_XHTML_CREATE;


    public function updateContent()
    {
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
            $folder = new Folder($this->wcms);
            $folder->setNewAsset($parentAsset);
            $folder->createAsset();
        }

    }

}