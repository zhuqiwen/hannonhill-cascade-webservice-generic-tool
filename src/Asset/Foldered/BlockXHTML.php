<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Asset\Containered\DataDefinition;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class BlockXHTML extends Block {
    protected $assetTypeDisplay = "Block";
    protected $assetTypeFetch = ASSET_BLOCK_XHTML_FETCH;
    protected $assetTypeCreate = ASSET_BLOCK_XHTML_CREATE;


    public function checkDependencies()
    {
        parent::checkDependencies();
        $this->checkExistenceDataDefinition($this->newAsset->structuredData->definitionPath);
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