<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSetContainer;
use Edu\IU\Wcms\WebService\WCMSClient;

class MetadataSetUtilities{

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
    }

    /**
     * fetch all metadata sets in a site
     * new site name and apikey can be passed in to fetch from different site
     * @param string $siteName
     * @param string $apiKey
     * @return array
     */
    public function getAllMetadata(string $siteName = '', string $apiKey = ''): array
    {
        if (!empty($siteName)){
            $this->wcms->setSiteName($siteName);
        }
        if (!empty($apiKey)){
            $this->wcms->setAuthByKey($apiKey);
        }

        return $this->getMetadataSetsInContainer('/', $this->wcms);

    }

    public function getMetadataSetsInContainer(string $containerPath):array
    {
        $result = [];

        $container = new MetadataSetContainer($this->wcms, $containerPath);

        $children = $this->convertToArrayWhenOnly1Child($container);
        foreach ($children as $child) {
            $tmpResult = match ($child->type){
                ASSET_CONTAINER_METADATA_SET_FETCH => $this->getMetadataSetsInContainer($child->path->path),
                ASSET_METADATA_SET_FETCH => [$child],
            };
            $result = array_merge($result, $tmpResult);
        }

        return $result;
    }

}