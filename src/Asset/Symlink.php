<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

class Symlink extends FolderContainedAsset implements AssetInterface {
    public $assetTypeDisplay = "Symlink";
    public $assetTypeFetch = ASSET_SYMLINK_FETCH;
    public $assetTypeCreate = ASSET_SYMLINK_CREATE;

    public function updateContent()
    {
        // TODO: Implement updateContent() method.
    }
}