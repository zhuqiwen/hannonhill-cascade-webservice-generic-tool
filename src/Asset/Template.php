<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

class Template extends Asset implements AssetInterface {
    public $assetTypeDisplay = "Template";
    public $assetTypeFetch = ASSET_TEMPLATE_FETCH;
    public $assetTypeCreate = ASSET_TEMPLATE_CREATE;

    public function updateContent()
    {
        // TODO: Implement updateContent() method.
    }
}
