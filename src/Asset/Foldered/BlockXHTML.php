<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Asset\Containered\DataDefinition;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class BlockXHTML extends Folder {
    public $assetTypeDisplay = "Block";
    public $assetTypeFetch = ASSET_BLOCK_XHTML_FETCH;
    public $assetTypeCreate = ASSET_BLOCK_XHTML_CREATE;


    public function checkDependencies(\stdClass $assetData)
    {
        //TODO: check content type
        $this->checkExistenceDataDefinition($assetData->structuredData->definitionPath);
    }

    public function checkExistenceDataDefinition($path)
    {
        $asset = new DataDefinition($this->wcms);
        $this->checkExistenceAndThrowException($asset, $path);
    }

    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetXHTMLOrDataDefinition($assetData);


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