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

        $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);

    }

    public function createParent()
    {
        $data = $this->prepareParentAssetForCreate();
        $asset = $data['parentAsset'];

        $folder = new Folder($this->wcms);
        $folder->setNewAsset($asset);
        $folder->createAsset();

    }

}