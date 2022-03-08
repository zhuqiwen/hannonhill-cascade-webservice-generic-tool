<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;


use Edu\IU\Wcms\WebService\WCMSClient;

class Asset
{
    use AssetTrait;


    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;

    }

}