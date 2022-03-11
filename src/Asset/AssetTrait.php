<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

trait AssetTrait
{

    protected $wcms;
    public $oldAsset;
    public $newAsset;
    public $assetTypeDisplay;
    public $assetTypeFetch;
    public $assetTypeCreate;



    public function setOldAsset(string $path)
    {
        if($this->assetExists($path))
        {
            $asset = $this->wcms->fetchAsset($path, $this->assetTypeFetch);
            $this->oldAsset = $asset->{$this->assetTypeCreate};
        }
        else
        {
            //TODO: set error and throw exception for user api key
        }
    }

    public function setNewAsset(\stdClass $assetData)
    {
        $this->newAsset = $assetData;
    }

    public function assetExists(string $path): bool
    {
        return $this->wcms->assetExists($path, $this->assetTypeFetch);
    }
}