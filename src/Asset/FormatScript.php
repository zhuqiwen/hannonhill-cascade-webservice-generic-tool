<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class FormatScript extends Asset{

    public function __construct(WCMSClient $wcms, string $path)
    {
        parent::__construct($wcms);
        $this->assetTypeDisplay = "Velocity Format";
        $this->assetTypeCreate = ASSET_FORMAT_SCRIPT_CREATE;
        $this->assetTypeFetch = ASSET_FORMAT_SCRIPT_FETCH;
        $this->setOldAsset($path);
    }

}