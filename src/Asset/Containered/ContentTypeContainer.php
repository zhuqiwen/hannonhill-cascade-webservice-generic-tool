<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class ContentTypeContainer extends Asset {
    use ContaineredAssetTrait;

    protected $assetTypeDisplay = "Content Type Container";
    protected $assetTypeFetch = ASSET_CONTAINER_CONTENT_TYPE_FETCH;
    protected $assetTypeCreate = ASSET_CONTAINER_CONTENT_TYPE_CREATE;
}