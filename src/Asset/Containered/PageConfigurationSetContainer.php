<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class PageConfigurationSetContainer extends Asset {
    use ContaineredAssetTrait;

    public $assetTypeDisplay = "Page Configuration Set Container";
    public $assetTypeFetch = ASSET_CONTAINER_PAGE_CONFIGURATION_SET_FETCH;
    public $assetTypeCreate = ASSET_CONTAINER_PAGE_CONFIGURATION_SET_CREATE;
}