<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

trait AssetTrait
{

    protected WCMSClient $wcms;
    public $oldAsset;
    public $newAsset;
    public $id;
    public $path;
    public $siteName;

}