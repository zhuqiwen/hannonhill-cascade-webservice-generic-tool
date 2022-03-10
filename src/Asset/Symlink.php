<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

class Symlink extends Asset{
    public $assetTypeDisplay = "Symlink";
    public $assetTypeFetch = ASSET_SYMLINK_FETCH;
    public $assetTypeCreate = ASSET_SYMLINK_CREATE;
}