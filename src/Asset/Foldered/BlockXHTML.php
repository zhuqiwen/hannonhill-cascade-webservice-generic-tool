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

}