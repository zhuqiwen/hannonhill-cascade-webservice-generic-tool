<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;


use Edu\IU\Framework\GenericUpdater\Asset\Containered\DataDefinition;
use Edu\IU\Wcms\WebService\WCMSClient;

class DataDefinitionUtilities{
    use UtilitiesTraits;

    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
        $this->assetTypeFetch = ASSET_DATA_DEFINITION_FETCH;
        $this->containerTypeFetch = ASSET_CONTAINER_DATA_DEFINITION_FETCH;

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


}