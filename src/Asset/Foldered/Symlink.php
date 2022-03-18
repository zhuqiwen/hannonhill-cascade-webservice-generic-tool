<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

class Symlink extends Folder {
    public $assetTypeDisplay = "Symlink";
    public $assetTypeFetch = ASSET_SYMLINK_FETCH;
    public $assetTypeCreate = ASSET_SYMLINK_CREATE;

}