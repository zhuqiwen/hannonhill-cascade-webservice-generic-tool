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


    public function checkDependencies()
    {
        parent::checkDependencies();
        $this->checkExistenceContentType($this->newAsset->contentTypePath);
        $this->checkExistenceDataDefinition($this->newAsset->structuredData->definitionPath);
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



}