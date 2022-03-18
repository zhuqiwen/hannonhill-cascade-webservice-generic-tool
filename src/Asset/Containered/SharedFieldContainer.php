<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class SharedFieldContainer extends Asset {
    use ContaineredAssetTrait;

    public $assetTypeDisplay = "Shared Field Container";
    public $assetTypeFetch = ASSET_CONTAINER_SHARED_FIELD_FETCH;
    public $assetTypeCreate = ASSET_CONTAINER_SHARED_FIELD_CREATE;
}