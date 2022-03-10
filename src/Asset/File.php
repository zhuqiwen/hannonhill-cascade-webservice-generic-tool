<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class File extends Asset{


    public function __construct(WCMSClient $wcms, string $path)
    {
        parent::__construct($wcms);
        $this->assetTypeDisplay = "File";
        $this->assetTypeFetch = ASSET_FILE_FETCH;
        $this->assetTypeCreate = ASSET_FILE_CREATE;
        $this->setOldAsset($path);
    }
}