<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;


use Edu\IU\Framework\GenericUpdater\Asset\Containered\DataDefinition;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\DataDefinitionContainer;
use Edu\IU\Framework\GenericUpdater\Asset\Foldered\Folder;
use Edu\IU\Wcms\WebService\WCMSClient;

class DataDefinitionUtilities implements UtilitiesInterface {
    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_DATA_DEFINITION_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_DATA_DEFINITION_FETCH;

        $this->assetClassName = DataDefinition::class;
        $this->assetContainerClassName = DataDefinitionContainer::class;

    }

    public function getAllPathsFromDataDefinition(string $dataDefinitionPath, string $siteName = '', string $apiKey = ""):array
    {
        if (!empty($siteName)){
            $this->setSiteName($siteName);
        }

        if (!empty($apiKey)){
            $this->setApiKey($apiKey);
        }

        $dataDefinition = new DataDefinition($this->wcms, $dataDefinitionPath);
        $xmlString = $dataDefinition->getXml();
        return $this->getAllPaths($xmlString);
    }

    private function getAllPaths(string $xmlString): array
    {
        $result = [];
        $xmlObj = simplexml_load_string($xmlString);
        foreach ($xmlObj->children() as $child) {
            $this->getPath($child,'', $result);
        }

        return $result;
    }


    private function getPath(\SimpleXMLElement $xmlObj, string $parentPath, array &$allPaths):void
    {

        if ($xmlObj->getName() == 'group'){
            //keep going down
            $parentPath = $parentPath . '/' . $xmlObj->attributes()['identifier'];
            foreach ($xmlObj->children() as $child) {
                $this->getPath($child, $parentPath, $allPaths);
            }
        }else{
            $nodeName = $xmlObj->getName();
            if (in_array($nodeName, ['text', 'asset'])){
                $path = $parentPath . '/' .  $xmlObj->attributes()['identifier'];
                $allPaths[] = $path;
            }
        }

    }

    public function generatePathMap(array $oldPathArray, array $newPathArray, string | array $preProcessMethodForOldPaths = '', string | array $preProcessMethodForNewPaths = ''):array
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