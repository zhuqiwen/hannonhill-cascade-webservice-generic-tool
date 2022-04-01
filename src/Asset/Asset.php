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
            // this is payload in json
            if(strpos(trim($inputs), '{') === 0)
            {
                $inputs = json_decode($inputs);
                $this->setNewAsset($inputs);
            }
            // this is path to existing asset
            else
            {
                $this->setOldAsset($inputs);
            }


        }

        if(gettype($inputs) == "array" || is_object($inputs))
        {
            $inputs = json_decode(json_encode($inputs));
            $this->setNewAsset($inputs);
        }

    }

    private function isValidJson(string $jsonString): bool
    {
        json_decode($jsonString);
        return json_last_error() == JSON_ERROR_NONE;
    }

}