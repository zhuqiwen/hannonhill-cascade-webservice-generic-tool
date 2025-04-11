<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\SharedField;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\SharedFieldContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Foldered\Folder;
use Edu\IU\Wcms\WebService\WCMSClient;

class SharedFieldUtilities implements UtilitiesInterface{
    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_SHARED_FIELD_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_SHARED_FIELD_FETCH;
        $this->assetClassName = SharedField::class;
        $this->assetContainerClassName = SharedFieldContainer::class;

    }
    public function getAllInContainer(string $containerOrFolderPath): array
    {
        $result = [];

        $container = new $this->assetContainerClassName($this->wcms, $containerOrFolderPath);

        $children = $this->convertToArrayWhenOnly1Child($container);
        foreach ($children as $child) {
            $tmpResult = match (true){
                $child->type == $this->containerTypeFetch => $this->getAllInContainer($child->path->path),
                //sharefield type can be 'sharedfield_GROUP', 'sharedfield_CHECKBOX', etc,
                //so we need to check if type string contains 'sharedfield_' (the one tailing with underscore), instead of 'sharedfield'
                str_contains($child->type, $this->assetTypeFetch . '_') => [$child],
                default => [],
            };
            $result = array_merge($result, $tmpResult);
        }

        return $result;
    }
}