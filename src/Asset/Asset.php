<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;


use Edu\IU\Wcms\WebService\WCMSClient;
use phpDocumentor\Reflection\Types\This;

class Asset implements AssetInterface
{
    use AssetTrait;


    public function __construct(WCMSClient $wcms, string $path)
    {
        $this->wcms = $wcms;
        $this->setOldAsset($path);
    }



}