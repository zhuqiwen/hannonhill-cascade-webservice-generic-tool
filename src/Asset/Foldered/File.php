<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


class File extends FolderContainedAsset {
    public $assetTypeDisplay = "File";
    public $assetTypeFetch = ASSET_FILE_FETCH;
    public $assetTypeCreate = ASSET_FILE_CREATE;

    public function updateContent()
    {
        // TODO: Implement updateContent() method.
    }
}