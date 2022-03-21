<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class DataDefinition extends DataDefinitionContainer {
    public $assetTypeDisplay = "Data Definition";
    public $assetTypeFetch = ASSET_DATA_DEFINITION_FETCH;
    public $assetTypeCreate = ASSET_DATA_DEFINITION_CREATE;

    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetXML($assetData);
        $this->checkIfValidXMLForDataDefinition($assetData->xml);
    }



    /**
     * @param string $xml
     * check if xml's root is <system-data-structure/>
     */
    public function checkIfValidXMLForDataDefinition(string $xml)
    {
        $xmlObj = simplexml_load_string($xml);
        if($xmlObj->getName() != "system-data-structure"){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", the value of [xml] should have root node named '<system-data-structure/>'" .PHP_EOL;
            throw new InputIntegrityException($msg);
        }
    }


    public function checkDependencies(\stdClass $assetData)
    {
        parent::checkDependencies($assetData);
        $this->checkExistencSharedFields($assetData->xml);

    }

    public function checkExistenceSharedFields(string $xml)
    {
        //TODO: get nodes of <shared-field>
        //

    }

}