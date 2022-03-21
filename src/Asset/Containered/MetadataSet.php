<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

class MetadataSet extends MetadataSetContainer {
    protected $assetTypeDisplay = "Metadata Set";
    protected $assetTypeFetch = ASSET_METADATA_SET_FETCH;
    protected $assetTypeCreate = ASSET_METADATA_SET_CREATE;

}