<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;
use Edu\IU\Wcms\WebService\WCMSClient;

trait UtilitiesTraits{
    public WCMSClient $wcms;

    protected string $assetTypeFetch;
    protected string $containerTypeFetch;

    protected string $assetClassName;

    public function getAssetClassName(): string
    {
        return $this->assetClassName;
    }

    public function convertToArrayWhenOnly1Child(Asset $containerOrFolder):array
    {

        $childrenProperty = $containerOrFolder->getOldAsset()->children;

        return isset($childrenProperty->child) ?
            (is_array($childrenProperty->child) ?
                $childrenProperty->child
                :
                [$childrenProperty->child])
            :
            [];
    }

    public function getAllInSite(string $siteName = '', string $apiKey = ''): array
    {
        $this->setSiteName($siteName);
        $this->setApiKey($apiKey);

        return $this->getAllInContainer('/');
    }

    public function setSiteName(string $siteName):void
    {
        if (!empty($siteName)){
            $this->wcms->setSiteName($siteName);
        }
    }

    public function setApiKey(string $apiKey):void
    {
        if (!empty($apiKey)){
            $this->wcms->setAuthByKey($apiKey);
        }
    }

    public function generatePathMap(array $oldPathArray, array $newPathArray, array $preProcessMethodForOldPaths = [], array $preProcessMethodForNewPaths = []):array
    {
        $map = [];
        foreach ($oldPathArray as $oldPath) {
            $similarity = 0;
            $map[$oldPath] = null;
            $processedOldPath = empty($preProcessMethodForOldPaths) ? $oldPath : call_user_func($preProcessMethodForOldPaths, $oldPath);
            //allow client application/codebase to skip some path/item in data definition
            if (empty($processedOldPath)){
                continue;
            }
            foreach ($newPathArray as $newPath) {
                $processedNewPath = empty($preProcessMethodForNewPaths) ? $newPath : call_user_func($preProcessMethodForNewPaths, $newPath);
                $tmpSimilarity = $this->pathComponentComparison($processedOldPath, $processedNewPath);
                if ($tmpSimilarity > $similarity){
                    $map[$oldPath] = $newPath;
                    $similarity = $tmpSimilarity;
                }
            }

        }

        return $map;
    }

    public function pathComponentComparison(string $pathA, string $pathB):float
    {
        $componentsA = explode(DIRECTORY_SEPARATOR, $pathA);
        $componentsB = explode(DIRECTORY_SEPARATOR, $pathB);

        $common = array_intersect_assoc($componentsA, $componentsB);

        return count($common) / max(count($componentsA), count($componentsB)) * 100;


    }

}