<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

class Page extends FolderContainedAsset {
    public  $assetTypeDisplay = "Page";
    public  $assetTypeFetch = ASSET_PAGE_FETCH;
    public  $assetTypeCreate = ASSET_PAGE_CREATE;

    public function updateContent()
    {
        
    }
}