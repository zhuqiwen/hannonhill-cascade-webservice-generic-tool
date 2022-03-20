<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Asset\Containered\ContentType;
use Edu\IU\Framework\GenericUpdater\Asset\Containered\DataDefinition;
use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class Page extends Folder {
    public  $assetTypeDisplay = "Page";
    public  $assetTypeFetch = ASSET_PAGE_FETCH;
    public  $assetTypeCreate = ASSET_PAGE_CREATE;


    public function checkDependencies(\stdClass $assetData)
    {
        //TODO: check content type
        $this->checkExistenceContentType($assetData->contentTypePath);
        $this->checkExistenceDataDefinition($assetData->structuredData->definitionPath);
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

    //TODO: check input integrity, such as structured data, parent path, name, etc

    public function checkInputIntegrity(\stdClass $asset)
    {
        parent::checkInputIntegrity($asset);
        $this->checkIfSetXHTMLOrDataDefinition($asset);


    }

    public function checkIfSetXHTMLOrDataDefinition(\stdClass $asset)
    {
        $hasXhtmlOrStructuredData = isset($asset->xhtml) && empty(trim($asset->xhtml));
        $hasXhtmlOrStructuredData = $hasXhtmlOrStructuredData
            ||
            (isset($asset->structuredData->definitionPath) && !empty($asset->structuredData->definitionPath));

        if(!$hasXhtmlOrStructuredData){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [structuredData][definitionPath] or [xhtml] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }
    }

}