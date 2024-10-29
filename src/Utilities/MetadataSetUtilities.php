<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSetContainer;
use Edu\IU\Wcms\WebService\WCMSClient;

class MetadataSetUtilities implements UtilitiesInterface {

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_METADATA_SET_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_METADATA_SET_FETCH;

        $this->assetClassName = 'Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSet';

    }


    public function getAllInContainer(string $containerOrFolderPath):array
    {
        $result = [];

        $container = new MetadataSetContainer($this->wcms, $containerOrFolderPath);

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