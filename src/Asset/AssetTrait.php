<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

trait AssetTrait
{

    protected $wcms;
    public $oldAsset;
    public $newAsset;

    public $assetTypeFetch;
    public $assetTypeCreate;
    public $assetTypeDisplay;
}