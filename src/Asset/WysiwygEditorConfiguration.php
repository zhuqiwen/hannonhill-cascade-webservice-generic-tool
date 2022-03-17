<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

class WysiwygEditorConfiguration extends Asset{

    public function getNewAssetPath()
    {
        throw new \RuntimeException("WYSIWYG Editor Configuration must not have path info");
    }

    public function getParentPathForCreate()
    {
        throw new \RuntimeException("WYSIWYG Editor Configuration must not have parent path info");
    }


}
