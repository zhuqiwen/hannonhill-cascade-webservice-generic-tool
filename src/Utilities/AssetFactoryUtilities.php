<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\AssetFactory;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\AssetFactoryContainer;
use Edu\IU\Wcms\WebService\WCMSClient;

class AssetFactoryUtilities implements UtilitiesInterface{
    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_ASSET_FACTORY_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_ASSET_FACTORY_FETCH;

        $this->assetClassName = AssetFactory::class;
        $this->assetContainerClassName = AssetFactoryContainer::class;
    }
}