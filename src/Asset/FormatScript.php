<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class FormatScript extends Asset{
    public $assetTypeDisplay = "Velocity Format";
    public $assetTypeCreate = ASSET_FORMAT_SCRIPT_CREATE;
    public $assetTypeFetch = ASSET_FORMAT_SCRIPT_FETCH;

}