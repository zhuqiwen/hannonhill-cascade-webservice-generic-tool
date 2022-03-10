<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class BlockXHTML extends Asset {

    public function __construct(WCMSClient $wcms, string $path)
    {
        parent::__construct($wcms);
        $this->assetTypeDisplay = "block";
        $this->assetTypeFetch = ASSET_BLOCK_XHTML_FETCH;
        $this->assetTypeCreate = ASSET_BLOCK_XHTML_CREATE;
        $this->setOldAsset($path);
    }

}