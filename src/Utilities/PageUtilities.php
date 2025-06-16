<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\ContentType;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\ContentTypeContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSet;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\MetadataSetContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\PageConfigurationSetContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Foldered\Folder;
use Edu\IU\Framework\GenericUpdater\Asset\Foldered\Page;
use Edu\IU\Framework\GenericUpdater\Interfaces\StructuredDataNodeInterface;
use Edu\IU\Wcms\WebService\WCMSClient;

class PageUtilities{

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_PAGE_FETCH;
        $this->containerTypeFetch = ASSET_FOLDER_FETCH;
        $this->assetClassName = Page::class;
        $this->assetContainerClassName = Folder::class;

    }

    public function echoProgressCLI(int $cnt):void
    {
            echo "\rnumber of Page found: $cnt";
    }


    /**
     * This method costs only 10~12 seconds for a site with ~2000 pages. Much faster
     * //TODO: need to rethink the logic
     * @param string $siteName
     * @param string $apiKey
     * @return array
     */
    public function getAllPagesInSite(string $siteName = '', string $apiKey = ''):array
    {
        $this->setSiteName($siteName);
        $this->setApiKey($apiKey);


        $result = [];
        $allPageInfo = $this->getAllPagesRelatedTo([new MetadataSetUtilities($this->wcms), new ContentTypeUtilities($this->wcms)]);
        foreach($allPageInfo as $pageInfo){
            if ($pageInfo->type == $this->assetTypeFetch && $pageInfo->path->siteName == $this->wcms->getSiteName()){
                $result[$pageInfo->id] = $pageInfo;
            }
        }
        return $result;
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
                    $result[$subscriber->id] = $subscriber;
                }
            }
        }

        return $result;
    }

    /**
     * the 3rd argument is expected to be array of:
     * 1. arrays
     * 2. OR objects that implement toArray() method
     * @param string $pagePath
     * @param string $contentTypePath
     * @param array $structuredDataNodes
     * @return \stdClass
     */
    //TODO: add 4th argument for metadataset
    public function constructAssetData(string $pagePath, string $contentTypePath, array $structuredDataNodes = [], string $metadataPath = '', ):\stdClass
    {
        $pagePath = trim($pagePath, DIRECTORY_SEPARATOR);
        $pageName = basename($pagePath);
        $parentFolderPath = dirname($pagePath) == '.' ? DIRECTORY_SEPARATOR : dirname($pagePath);

        return (object)[
            'name' => $pageName,
            'parentFolderPath' => $parentFolderPath,
            'contentTypePath' => $contentTypePath,
            'metadataSetPath' => $metadataPath,
            'structuredData' => (object) [
                'structuredDataNodes' => (object) [
                    'structuredDataNode' => $this->normalizeStructuredDataNodesArray($structuredDataNodes)
                ]
            ]
        ];
    }




    /**
     * @param array $structuredDataNodes, array of objects that implement StructuredDataNodeInterface
     * @return array
     */
    private function normalizeStructuredDataNodesArray(array $structuredDataNodes):array
    {
        $structuredDataNodeArray = [];
        foreach ($structuredDataNodes as $structuredDataNode) {
            if ($structuredDataNode instanceof StructuredDataNodeInterface){
                $structuredDataNodeArray[] = $structuredDataNode->toArray();
            }else{
                throw new \RuntimeException('the 3rd argument, \$structuredDataNodes must be either array of objects that implement Edu\IU\Framework\GenericUpdater\Interfaces');
            }
        }

        return $structuredDataNodeArray;
    }

}