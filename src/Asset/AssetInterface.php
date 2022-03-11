<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

interface AssetInterface
{
    public function setOldAsset(string $path);
    public function setNewAsset(\stdClass $assetData);

    public function assetExists(string $path);

    public function updateContent();
}