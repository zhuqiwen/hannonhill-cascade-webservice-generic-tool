<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Container\ContaineredAsset;

class AssetFactory extends ContaineredAsset {
    public $assetTypeDisplay = "Asset Factory";
    public $assetTypeFetch = ASSET_ASSET_FACTORY_FETCH;
    public $assetTypeCreate = ASSET_ASSET_FACTORY_CREATE;

    public function updateContent()
    {
        // TODO: Implement updateContent() method.
    }
}