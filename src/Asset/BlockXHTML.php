<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class BlockXHTML extends Asset {
    public $assetTypeDisplay = "block";
    public $assetTypeFetch = ASSET_BLOCK_XHTML_FETCH;
    public $assetTypeCreate = ASSET_BLOCK_XHTML_CREATE;

}