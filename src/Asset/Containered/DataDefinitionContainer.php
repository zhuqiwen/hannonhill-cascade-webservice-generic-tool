<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class DataDefinitionContainer extends Asset {
    use ContaineredAssetTrait;

    protected $assetTypeDisplay = "Data Definition Container";
    protected $assetTypeFetch = ASSET_CONTAINER_DATA_DEFINITION_FETCH;
    protected $assetTypeCreate = ASSET_CONTAINER_DATA_DEFINITION_CREATE;
}