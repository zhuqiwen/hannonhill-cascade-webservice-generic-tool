<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

class BlockXHTML extends FolderContainedAsset implements AssetInterface {
    public $assetTypeDisplay = "Block";
    public $assetTypeFetch = ASSET_BLOCK_XHTML_FETCH;
    public $assetTypeCreate = ASSET_BLOCK_XHTML_CREATE;


    public function updateContent()
    {
    }



}