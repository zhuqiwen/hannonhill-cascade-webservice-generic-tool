<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class AssetFactoryContainer extends Asset {
    use ContaineredAssetTrait;

    protected $assetTypeDisplay = "Asset Factory Container";
    protected $assetTypeFetch = ASSET_CONTAINER_ASSET_FACTORY_FETCH;
    protected $assetTypeCreate = ASSET_CONTAINER_ASSET_FACTORY_CREATE;
}