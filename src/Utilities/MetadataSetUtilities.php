<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSet;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSetContainer;
use Edu\IU\Wcms\WebService\WCMSClient;

class MetadataSetUtilities implements UtilitiesInterface {

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_METADATA_SET_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_METADATA_SET_FETCH;

        $this->assetClassName = MetadataSet::class;
        $this->assetContainerClassName = MetadataSetContainer::class;

    }



}