<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class AssetFactoryContainer extends Asset {
    use ContaineredAssetTrait;

    public $assetTypeDisplay = "Asset Factory Container";
    public $assetTypeFetch = ASSET_CONTAINER_ASSET_FACTORY_FETCH;
    public $assetTypeCreate = ASSET_CONTAINER_ASSET_FACTORY_CREATE;
}