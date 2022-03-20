<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\WysiwygEditorConfiguration;
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

        $this->checkExistencePageConfigurationSet($assetData->pageConfigurationSetPath);
        $this->checkExistenceMetadataSet($assetData->metadataSetPath);
        //since Data Definition path is not required when creating a Content Type
        if(isset($assetData->dataDefinitionPath) && !empty(trim($assetData->dataDefinitionPath))){
            $this->checkExistenceDataDefinition($assetData->dataDefinitionPath);
        }
        //since editor configuration is not required when creating a Content Type
        if(isset($assetData->editorConfigurationPath) && !empty(trim($assetData->editorConfigurationPath))){
            $this->checkExistenceEditorConfiguration($assetData->editorConfigurationPath);
        }


    }

    public function checkExistencePageConfigurationSet(string $path)
    {
        $asset = new PageConfigurationSet($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }

    public function checkExistenceMetadataSet(string $path)
    {
        $asset = new MetadataSet($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }

    public function checkExistenceDataDefinition(string $path)
    {
        $asset = new DataDefinition($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }

    public function checkExistenceEditorConfiguration(string $path)
    {
        $asset = new WysiwygEditorConfiguration($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }



}