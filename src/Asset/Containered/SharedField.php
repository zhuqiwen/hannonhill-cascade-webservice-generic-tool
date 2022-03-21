<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

class SharedField extends SharedFieldContainer {
    protected $assetTypeDisplay = "Shared Field";
    protected $assetTypeFetch = ASSET_SHARED_FIELD_FETCH;
    protected $assetTypeCreate = ASSET_SHARED_FIELD_CREATE;
}