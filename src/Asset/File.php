<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class File extends Asset{
    public $assetTypeDisplay = "File";
    public $assetTypeFetch = ASSET_FILE_FETCH;
    public $assetTypeCreate = ASSET_FILE_CREATE;

}