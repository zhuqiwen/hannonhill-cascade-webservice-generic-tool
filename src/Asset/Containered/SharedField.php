<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

class SharedField extends SharedFieldContainer {
    public $assetTypeDisplay = "Shared Field";
    public $assetTypeFetch = ASSET_SHARED_FIELD_FETCH;
    public $assetTypeCreate = ASSET_SHARED_FIELD_CREATE;
}