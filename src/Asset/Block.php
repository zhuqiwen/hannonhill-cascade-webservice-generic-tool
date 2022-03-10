<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;
use Edu\IU\Framework\GenericUpdater\ConstantsConstructor;

class Block extends Asset {

    public function __construct(WCMSClient $wcms)
    {
        parent::__construct($wcms);
        $this->assetTypeDisplay = "block";
        $this->assetTypeFetch = ASSET_BLOCK_FETCH;
        $this->assetTypeCreate = ASSET_BLOCK_CREATE;
    }

}