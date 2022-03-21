<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class SharedFieldContainer extends Asset {
    use ContaineredAssetTrait;

    protected $assetTypeDisplay = "Shared Field Container";
    protected $assetTypeFetch = ASSET_CONTAINER_SHARED_FIELD_FETCH;
    protected $assetTypeCreate = ASSET_CONTAINER_SHARED_FIELD_CREATE;
}