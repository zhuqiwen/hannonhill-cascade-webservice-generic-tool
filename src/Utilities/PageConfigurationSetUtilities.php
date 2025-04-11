<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSetContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\PageConfigurationSet;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\PageConfigurationSetContainer;
use Edu\IU\Wcms\WebService\WCMSClient;

class PageConfigurationSetUtilities implements UtilitiesInterface {

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_PAGE_CONFIGURATION_SET_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_PAGE_CONFIGURATION_SET_FETCH;

        $this->assetClassName = PageConfigurationSet::class;
        $this->assetContainerClassName = PageConfigurationSetContainer::class;
    }

}