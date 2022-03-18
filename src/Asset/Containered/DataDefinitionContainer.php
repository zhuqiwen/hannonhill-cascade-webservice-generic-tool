<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class DataDefinitionContainer extends Asset {
    use ContaineredAssetTrait;

    public $assetTypeDisplay = "Data Definition Container";
    public $assetTypeFetch = ASSET_CONTAINER_DATA_DEFINITION_FETCH;
    public $assetTypeCreate = ASSET_CONTAINER_DATA_DEFINITION_CREATE;
}