<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class MetadataSetContainer extends Asset {
    use ContaineredAssetTrait;

    public $assetTypeDisplay = "Metadata Set Container";
    public $assetTypeFetch = ASSET_CONTAINER_METADATA_SET_FETCH;
    public $assetTypeCreate = ASSET_CONTAINER_METADATA_SET_CREATE;

}