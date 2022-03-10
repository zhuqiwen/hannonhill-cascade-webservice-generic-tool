<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;


use Edu\IU\Wcms\WebService\WCMSClient;
use phpDocumentor\Reflection\Types\This;

class Asset implements AssetInterface
{
    use AssetTrait;


    public function __construct(WCMSClient $wcms)
    {
        $this->wcms = $wcms;
    }

    public function setOldAsset(string $path)
    {
        if($this->assetExists($path))
        {
            $assetType = $this->assetTypeCreate;
            $this->oldAsset = $this->wcms->fetchAsset($path, $this->assetTypeFetch)->$assetType;
        }
        else
        {
            //TODO: set error and throw exception for user api key
        }
    }

    public function setNewAsset(\stdClass $assetData)
    {
        // TODO: Implement setNewAsset() method.
        $this->newAsset = $assetData;
    }

    public function assetExists(string $path): bool
    {
        return $this->wcms->assetExists($path, $this->assetTypeFetch);
    }

}