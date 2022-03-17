<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;


class DataDefinition extends Asset implements AssetInterface {
    public $assetTypeDisplay = "Data Definition";
    public $assetTypeFetch = ASSET_DATA_DEFINITION_FETCH;
    public $assetTypeCreate = ASSET_DATA_DEFINITION_CREATE;

    public function updateContent()
    {
        // TODO: Implement updateContent() method.
    }
}