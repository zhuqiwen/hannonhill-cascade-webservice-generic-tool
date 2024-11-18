<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;
use Edu\IU\Wcms\WebService\WCMSClient;

trait UtilitiesTraits{
    public WCMSClient $wcms;

    protected string $assetTypeFetch;
    protected string $containerTypeFetch;

    protected string $assetClassName;

    public function getAssetClassName(): string
    {
        return $this->assetClassName;
    }

    public function convertToArrayWhenOnly1Child(Asset $containerOrFolder):array
    {

        $childrenProperty = $containerOrFolder->getOldAsset()->children;

        return isset($childrenProperty->child) ?
            (is_array($childrenProperty->child) ?
                $childrenProperty->child
                :
                [$childrenProperty->child])
            :
            [];
    }

    public function getAllInSite(string $siteName = '', string $apiKey = ''): array
    {
        $this->setSiteName($siteName);
        $this->setApiKey($apiKey);

        return $this->getAllInContainer('/');
    }

    public function setSiteName(string $siteName):void
    {
        if (!empty($siteName)){
            $this->wcms->setSiteName($siteName);
        }
    }

    public function setApiKey(string $apiKey):void
    {
        if (!empty($apiKey)){
            $this->wcms->setAuthByKey($apiKey);
        }
    }



}