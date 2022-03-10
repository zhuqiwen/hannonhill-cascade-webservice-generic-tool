<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

use Edu\IU\Wcms\WebService\WCMSClient;

class Page extends Asset{
    public  $assetTypeDisplay = "Page";
    public  $assetTypeFetch = ASSET_PAGE_FETCH;
    public  $assetTypeCreate = ASSET_PAGE_CREATE;

}