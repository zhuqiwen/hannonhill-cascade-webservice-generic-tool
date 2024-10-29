<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSetContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\PageConfigurationSetContainer;
use Edu\IU\Wcms\WebService\WCMSClient;

class PageConfigurationSetUtilities implements UtilitiesInterface {

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_PAGE_CONFIGURATION_SET_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_PAGE_CONFIGURATION_SET_FETCH;

        $this->assetClassName = 'Edu\IU\Framework\GenericUpdater\Asset\Foldered\Page';
    }

    public function getAllInContainer(string $containerOrFolderPath): array
    {
        $result = [];

        $container = new PageConfigurationSetContainer($this->wcms, $containerOrFolderPath);

        $children = $this->convertToArrayWhenOnly1Child($container);
        foreach ($children as $child) {
            $tmpResult = match ($child->type){
                $this->containerTypeFetch => $this->getAllInContainer($child->path->path),
                $this->assetTypeFetch => [$child],
            };
            $result = array_merge($result, $tmpResult);
        }

        return $result;
    }
}