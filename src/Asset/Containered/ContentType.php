<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

class ContentType extends Asset implements AssetInterface {
    public $assetTypeDisplay = "Content Type";
    public $assetTypeFetch = ASSET_CONTENT_TYPE_FETCH;
    public $assetTypeCreate = ASSET_CONTENT_TYPE_CREATE;

    public function updateContent()
    {
        // TODO: Implement updateContent() method.
    }
}