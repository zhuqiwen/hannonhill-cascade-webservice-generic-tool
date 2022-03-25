<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class Asset
{
    use AssetTrait;


    public function __construct(WCMSClient $wcms, $inputs = null)
    {
        $this->wcms = $wcms;
        $this->siteName = $wcms->getSiteName();
        if(gettype($inputs) == "string" && !empty(trim($inputs)))
        {
            $this->setOldAsset($inputs);
        }

        if(gettype($inputs) == "array")
        {
            $inputs = json_decode(json_encode($inputs));
            $this->setNewAsset($inputs);
        }

    }

}