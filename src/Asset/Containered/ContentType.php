<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\WysiwygEditorConfiguration;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class ContentType extends ContentTypeContainer {
    protected $assetTypeDisplay = "Content Type";
    protected $assetTypeFetch = ASSET_CONTENT_TYPE_FETCH;
    protected $assetTypeCreate = ASSET_CONTENT_TYPE_CREATE;






    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetPageConfigurationSetPath();
        $this->checkIfSetMetaDataSetPath();
    }

    public function checkIfSetPageConfigurationSetPath()
    {
        $pathExists = isset($this->newAsset->pageConfigurationSetPath)
            ||
            !empty(trim($this->newAsset->pageConfigurationSetPath));
        if(!$pathExists){
            $msg = $this->getClassName();
            $msg .= " payload:";
            $msg .= " [pageConfigurationSetPath] => 'PATH-TO-Page-Configuration' is missing or has empty value";
            throw new InputIntegrityException($msg);
        }
    }

    public function checkIfSetMetaDataSetPath()
    {
        $pathExists = isset($this->newAsset->metadataSetPath)
            ||
            !empty(trim($this->newAsset->metadataSetPath));
        if(!$pathExists){
            $msg = $this->getClassName();
            $msg .= " payload:";
            $msg .= " [metadataSetPath] => 'PATH-TO-Metadata-Set' is missing or has empty value";
            throw new InputIntegrityException($msg);
        }
    }

    public function checkDependencies()
    {

        $this->checkExistencePageConfigurationSet($this->newAsset->pageConfigurationSetPath);
        $this->checkExistenceMetadataSet($this->newAsset->metadataSetPath);
        //since Data Definition path is not required when creating a Content Type
        if(isset($this->newAsset->dataDefinitionPath) && !empty(trim($this->newAsset->dataDefinitionPath))){
            $this->checkExistenceDataDefinition($this->newAsset->dataDefinitionPath);
        }
        //since editor configuration is not required when creating a Content Type
        if(isset($this->newAsset->editorConfigurationPath) && !empty(trim($this->newAsset->editorConfigurationPath))){
            $this->checkExistenceEditorConfiguration($this->newAsset->editorConfigurationPath);
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