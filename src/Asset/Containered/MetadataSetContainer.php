<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class MetadataSetContainer extends Asset {
    use ContaineredAssetTrait;

    protected $assetTypeDisplay = "Metadata Set Container";
    protected $assetTypeFetch = ASSET_CONTAINER_METADATA_SET_FETCH;
    protected $assetTypeCreate = ASSET_CONTAINER_METADATA_SET_CREATE;

}