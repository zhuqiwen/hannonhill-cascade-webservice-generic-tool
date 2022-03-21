<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class PageConfigurationSetContainer extends Asset {
    use ContaineredAssetTrait;

    protected $assetTypeDisplay = "Page Configuration Set Container";
    protected $assetTypeFetch = ASSET_CONTAINER_PAGE_CONFIGURATION_SET_FETCH;
    protected $assetTypeCreate = ASSET_CONTAINER_PAGE_CONFIGURATION_SET_CREATE;
}