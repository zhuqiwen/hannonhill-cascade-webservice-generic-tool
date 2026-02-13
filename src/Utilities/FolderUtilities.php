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

class FolderUtilities{

    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_FOLDER_FETCH;
        $this->containerTypeFetch = ASSET_FOLDER_FETCH;
        $this->assetClassName = Folder::class;
        $this->assetContainerClassName = Folder::class;

    }

    public function echoProgressCLI(int $cnt):void
    {
            echo "\rnumber of Folders found: $cnt";
    }

    public function getAllInContainer(string $containerOrFolderPath): array
    {
        $result = [];

        $container = new $this->assetContainerClassName($this->wcms, $containerOrFolderPath);

        $children = $this->convertToArrayWhenOnly1Child($container);
        foreach ($children as $child) {
            $tmpResult = [];
            if ($child->type == $this->assetTypeFetch){
                $tmpResult = [$child];
                $tmpResult = array_merge($this->getAllInContainer($child->path->path), $tmpResult);
            }
            $result = array_merge($result, $tmpResult);
        }

        return $result;

    }


    /**
     * This method costs only 10~12 seconds for a site with ~2000 pages. Much faster
     * //TODO: need to rethink the logic
     * @param string $siteName
     * @param string $apiKey
     * @return array
     */
    public function getAllFoldersInSiteRelatedTo(string $siteName = '', string $apiKey = ''):array
    {
        $this->setSiteName($siteName);
        $this->setApiKey($apiKey);


        $result = [];
        $allFolderInfo = $this->getAllFoldersRelatedTo([new MetadataSetUtilities($this->wcms), new ContentTypeUtilities($this->wcms)]);
        foreach($allFolderInfo as $folderInfo){
            if ($folderInfo->type == $this->assetTypeFetch && $folderInfo->path->siteName == $this->wcms->getSiteName()){
                $result[$folderInfo->id] = $folderInfo;
            }
        }
        return $result;
    }

    public function getAllFoldersRelatedTo(array $arrayOfAssetUtilityObjects): array
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
    public function constructAssetData(string $folderPath, string $metadataPath = '', ):\stdClass
    {
        $folderPath = trim($folderPath, DIRECTORY_SEPARATOR);
        $folderName = basename($folderPath);
        $parentFolderPath = dirname($folderPath) == '/' ? '' : dirname($folderPath);

        return (object)[
            'name' => $folderName,
            'parentFolderPath' => $parentFolderPath,
            'metadataSetPath' => $metadataPath,
        ];
    }


}