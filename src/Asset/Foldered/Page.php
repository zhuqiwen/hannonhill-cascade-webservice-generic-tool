<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\ContentType;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\DataDefinition;
use phpDocumentor\Reflection\Types\This;

class Page extends Folder {
    protected  $assetTypeDisplay = "Page";
    protected  $assetTypeFetch = ASSET_PAGE_FETCH;
    protected  $assetTypeCreate = ASSET_PAGE_CREATE;


    public function checkDependencies()
    {
        parent::checkDependencies();
        $this->checkExistenceContentType($this->newAsset->contentTypePath);
        //no need to check if dd exists, dd path is not required for a page
//        $this->checkExistenceDataDefinition($this->newAsset->structuredData->definitionPath);
    }

    public function checkExistenceContentType($path)
    {
        $asset = new ContentType($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);

    }

    public function checkExistenceDataDefinition($path)
    {
        $asset = new DataDefinition($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }

    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetXHTMLOrDataDefinition();


    }

    public function getOldStructuredDataNode():array
    {
        $result = $this->getOldAsset()->structuredData->structuredDataNodes->structuredDataNode;
        // not array, and is a stdClass, meaning there is either only one node, or the content type is using a wysiwyg editor
        if (!is_array($result) && $result instanceof \stdClass){
            $result = [$result];
        }

        return $result;
    }



}