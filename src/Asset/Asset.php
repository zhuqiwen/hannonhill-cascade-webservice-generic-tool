<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class Asset
{
    use AssetTrait;


    public function __construct(WCMSClient $wcms, string $path = "")
    {
        $this->wcms = $wcms;
        if(!empty($path))
        {
            $this->setOldAsset($path);
        }

    }

}