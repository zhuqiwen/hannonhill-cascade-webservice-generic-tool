<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Asset\Asset;

class FolderContainedAsset extends Asset {
    public $containerClassName = 'Folder';

    public function getNewAssetPath()
    {
        $path = DIRECTORY_SEPARATOR
            . trim($this->newAsset->parentFolderPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . trim($this->newAsset->name);

        return $path;
    }






}