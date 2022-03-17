<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

class MetadataSet extends ContaineredAsset {
    public $assetTypeDisplay = "Metadata Set";
    public $assetTypeFetch = ASSET_METADATA_SET_FETCH;
    public $assetTypeCreate = ASSET_METADATA_SET_CREATE;
    public $containerClassName = 'MetadataSetContainer';

}