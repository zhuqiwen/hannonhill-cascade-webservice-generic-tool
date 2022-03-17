<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Wcms\WebService\WCMSClient;

class FormatScript extends FolderContainedAsset implements AssetInterface {
    public $assetTypeDisplay = "Velocity Format";
    public $assetTypeCreate = ASSET_FORMAT_SCRIPT_CREATE;
    public $assetTypeFetch = ASSET_FORMAT_SCRIPT_FETCH;

    public function updateContent()
    {
        // TODO: Implement updateContent() method.
    }
}