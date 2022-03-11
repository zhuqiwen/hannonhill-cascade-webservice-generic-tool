<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

class AssetFactory extends Asset implements AssetInterface {
    public $assetTypeDisplay = "Asset Factory";
    public $assetTypeFetch = ASSET_ASSET_FACTORY_FETCH;
    public $assetTypeCreate = ASSET_ASSET_FACTORY_CREATE;

    public function updateContent()
    {
        // TODO: Implement updateContent() method.
    }
}