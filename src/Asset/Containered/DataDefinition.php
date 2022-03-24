<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class DataDefinition extends DataDefinitionContainer {
    protected $assetTypeDisplay = "Data Definition";
    protected $assetTypeFetch = ASSET_DATA_DEFINITION_FETCH;
    protected $assetTypeCreate = ASSET_DATA_DEFINITION_CREATE;

    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetXML();
        $this->checkIfValidXMLForDataDefinition();
    }



    /**
     * @param string $xml
     * check if xml's root is <system-data-structure/>
     */
    public function checkIfValidXMLForDataDefinition()
    {
        $xmlObj = simplexml_load_string($this->newAsset->xml);
        if($xmlObj->getName() != "system-data-structure"){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", the value of [xml] should have root node named '<system-data-structure/>'" .PHP_EOL;
            throw new InputIntegrityException($msg);
        }
    }


    public function checkDependencies()
    {
        parent::checkDependencies();
        $this->checkExistenceSharedFields();

    }

    public function checkExistenceSharedFields()
    {
        if(!isset($this->newAsset->xml) || empty($this->newAsset->xml)){
            $msg = "For Asset: " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [xml] should be set and the value should valid xml for " . $this->assetTypeDisplay;
            $msg .= PHP_EOL;
            throw new \RuntimeException($msg);
        }

        $xmlObj = simplexml_load_string($this->newAsset->xml);
        $sharedFieldNodes = $xmlObj->xpath("//shared-field");

        foreach ($sharedFieldNodes as $index => $node){
            //keep current site name for switch back
            $currentSiteName = $this->wcms->getSiteName();
            //check if shared field is from another site; crossing-site dependency
            $otherSiteName = $this->getSiteNameFromAssetPath($node['path']);
            $path = $node['path'];
            $path = str_replace("site://", "" , $path);
            $path = str_replace($otherSiteName, "" , $path);

            if(!empty($otherSiteName)){
                $this->wcms->setSiteName($otherSiteName);
            }

            $sharedField = new SharedField($this->wcms);
            $this->checkExistenceAndThrowException($sharedField, $path);
            //switch back site name
            $this->wcms->setSiteName($currentSiteName);
        }

    }


}