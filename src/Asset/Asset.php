<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class Asset
{
    use AssetTrait;


    public function __construct(WCMSClient $wcms, $inputs = null, bool $skipCheckDependencies = false, bool $printAssetDetails = false)
    {
        $this->skipCheckDependencies = $skipCheckDependencies;
        $this->printAssetDetails = $printAssetDetails;
        $this->wcms = $wcms;
        $this->siteName = $wcms->getSiteName();


        match (true){
            is_null($inputs) => $this->setOldAssetWithEmptyObj(),
            is_string($inputs) && str_starts_with(trim($inputs), '{') => $this->setNewAssetWithJson($inputs),
            is_array($inputs) || is_object($inputs) => $this->setNewAssetWithArrayOrObject($inputs),
            default =>$this->setOldAssetWithAssetPath($inputs),
        };

    }
    
    private function setOldAssetWithEmptyObj():void
    {
        $this->oldAsset = new \stdClass();
    }
    private function setOldAssetWithAssetPath(string $path):void
    {
        $this->setOldAsset($path);
    }
    
    private function setNewAssetWithJson(string $json):void
    {
        $inputs = json_decode($json);
        $this->setNewAsset($inputs);
    }

    private function setNewAssetWithArrayOrObject(array | object $inputs):void
    {
        $inputs = json_decode(json_encode($inputs));
        $this->setNewAsset($inputs);
    }

    private function isValidJson(string $jsonString): bool
    {
        json_decode($jsonString);
        return json_last_error() == JSON_ERROR_NONE;
    }


    public function getWCMS()
    {
        return $this->wcms;
    }

}