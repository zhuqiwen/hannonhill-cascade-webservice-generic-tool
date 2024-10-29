<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\ContentType;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\ContentTypeContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSet;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSetContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\PageConfigurationSetContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Foldered\Folder;
use Edu\IU\Wcms\WebService\WCMSClient;

class PageUtilities{

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_PAGE_FETCH;
        $this->containerTypeFetch = ASSET_FOLDER_FETCH;

    }

    /**
     * This method scans all folders for pages in a site, so it can be slow when the site has a large number of pages
     * for a site with ~2000 pages, it costs about 4~5 minutes
     * @param string $containerOrFolderPath
     * @return array
     */
    public function getAllInContainer(string $containerOrFolderPath): array
    {
        $result = [];

        $container = new Folder($this->wcms, $containerOrFolderPath);

        $children = $this->convertToArrayWhenOnly1Child($container);
        foreach ($children as $child) {
            $tmpResult = match ($child->type){
                $this->containerTypeFetch => $this->getAllInContainer($child->path->path),
                $this->assetTypeFetch => [$child],
                //ignore files, blocks, and velocity formats
                default => [],
            };
            $result = array_merge($result, $tmpResult);
        }

        return $result;
    }

    public function echoProgressCLI(int $cnt):void
    {
            echo "\rnumber of Page found: $cnt";
    }


    /**
     * This method costs only 10~12 seconds for a site with ~2000 pages. Much faster
     * @param string $siteName
     * @param string $apiKey
     * @return array
     */
    public function getAllPagesInSite(string $siteName = '', string $apiKey = ''):array
    {
        $this->setSiteName($siteName);
        $this->setApiKey($apiKey);


        return $this->getAllPagesRelatedTo([new MetadataSetUtilities($this->wcms), new ContentTypeUtilities($this->wcms)]);

    }

    public function getAllPagesRelatedTo(array $arrayOfAssetUtilityObjects): array
    {
        $result = [];
        foreach ($arrayOfAssetUtilityObjects as $assetUtilityObject) {
            $allAssets = $assetUtilityObject->getAllInSite();
            foreach ($allAssets as $assetInfo){
                $class = $assetUtilityObject->getAssetClassName();
                $asset = new $class($this->wcms, $assetInfo->path->path);
                $subscribers = $asset->listSubscribers();
                foreach ($subscribers as $subscriber) {
                    if ($subscriber->type == $this->assetTypeFetch && $subscriber->path->siteName == $this->wcms->getSiteName()){
                        $result[$subscriber->id] = $subscriber;
                    }
                }
            }
        }

        return $result;
    }
}