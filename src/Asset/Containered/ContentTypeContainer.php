<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class ContentTypeContainer extends Asset {
    use ContaineredAssetTrait;

    public $assetTypeDisplay = "Content Type Container";
    public $assetTypeFetch = ASSET_CONTAINER_CONTENT_TYPE_FETCH;
    public $assetTypeCreate = ASSET_CONTAINER_CONTENT_TYPE_CREATE;
}