<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;


use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class DataDefinition extends DataDefinitionContainer {
    public $assetTypeDisplay = "Data Definition";
    public $assetTypeFetch = ASSET_DATA_DEFINITION_FETCH;
    public $assetTypeCreate = ASSET_DATA_DEFINITION_CREATE;

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
        $xmlObj = simplexml_load_string($$this->newAsset->xml);
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
        $xml = $this->newAsset->xml;
        //TODO: get nodes of <shared-field>
        //

    }

}