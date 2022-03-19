<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class ContentType extends ContentTypeContainer {
    public $assetTypeDisplay = "Content Type";
    public $assetTypeFetch = ASSET_CONTENT_TYPE_FETCH;
    public $assetTypeCreate = ASSET_CONTENT_TYPE_CREATE;






    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetPageConfigurationSetPath($assetData);
        $this->checkIfSetMetaDataSetPath($assetData);
    }

    public function checkIfSetPageConfigurationSetPath(\stdClass $assetData)
    {
        $pathExists = isset($assetData->pageConfigurationSetPath)
            ||
            !empty(trim($assetData->pageConfigurationSetPath));
        if(!$pathExists){
            $msg = $this->getClassName();
            $msg .= " payload:";
            $msg .= " [pageConfigurationSetPath] => 'PATH-TO-Page-Configuration' is missing or has empty value";
            throw new InputIntegrityException($msg);
        }
    }

    public function checkIfSetMetaDataSetPath(\stdClass $assetData)
    {
        $pathExists = isset($assetData->metadataSetPath)
            ||
            !empty(trim($assetData->metadataSetPath));
        if(!$pathExists){
            $msg = $this->getClassName();
            $msg .= " payload:";
            $msg .= " [metadataSetPath] => 'PATH-TO-Metadata-Set' is missing or has empty value";
            throw new InputIntegrityException($msg);
        }
    }

    public function checkDependencies(\stdClass $assetData)
    {
        //TODO: check pageconfiguration set
        //TODO: check metadata set
        //TODO: if data definition path in payload, check it as well

    }



}