<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\ContentType;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\ContentTypeContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSetContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\PageConfigurationSetContainer;
use Edu\IU\Wcms\WebService\WCMSClient;

class ContentTypeUtilities implements UtilitiesInterface {

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_CONTENT_TYPE_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_CONTENT_TYPE_FETCH;

        $this->assetClassName = ContentType::class;
        $this->assetContainerClassName = ContentTypeContainer::class;
    }

}