<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;
use Edu\IU\Wcms\WebService\WCMSClient;
use phpDocumentor\Reflection\Types\This;

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

    /**
     * use $replaceSharedFieldsWithTheirXml to control if the xml returned contains <shared-field/>
     * @param bool $replaceSharedFieldsWithTheirXml
     * @return string
     */
    public function getXml(bool $replaceSharedFieldsWithTheirXml = true):string
    {
        return $replaceSharedFieldsWithTheirXml ? $this->getXmlWhereSharedFieldsConverted() : $this->getOriginalXmlString();
    }


    /**
     * get data definition's xml string where shared-field nodes are NOT converted
     * @return string
     */
    public function getOriginalXmlString():string
    {
        return $this->getOldAsset()->xml;
    }

    /**
     * get data definition's xml string, where all shared-field nodes are fetched and replaced with their xml
     * @return string
     */
    public function getXmlWhereSharedFieldsConverted():string
    {
        $ddXml = $this->getOldAsset()->xml;
        $ddXmlDom = new \DOMDocument();
        $ddXmlDom->loadXML($ddXml);

        $sharedFieldNodeList = $ddXmlDom->getElementsByTagName('shared-field');

        //iterator_to_array() is required here
        // because we are going to make changes to the dom document by replacing shared-field elements with their actual xml
        foreach (iterator_to_array($sharedFieldNodeList) as $sharedFieldNode) {

            $sfPath = $sharedFieldNode->getAttribute('path');
            $sf = new SharedField($this->wcms, $sfPath);
            $sfXml = $sf->getOldAsset()->xml;
            $sfXmlDom = new \DOMDocument();
            $sfXmlDom->loadXML($sfXml);
            $sfXmlNode = $ddXmlDom->importNode($sfXmlDom->documentElement->firstElementChild, true);
            $sharedFieldNode->replaceWith($sfXmlNode);

        }

        return $ddXmlDom->saveXML();
    }



}