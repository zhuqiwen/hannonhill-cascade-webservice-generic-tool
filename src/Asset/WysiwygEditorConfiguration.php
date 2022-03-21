<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

class WysiwygEditorConfiguration extends Asset{

    protected  $assetTypeDisplay = "WYSIWYG Editor Configuration";
    protected  $assetTypeFetch = ASSET_EDITOR_CONFIGURATION_FETCH;
    protected  $assetTypeCreate = ASSET_EDITOR_CONFIGURATION_CREATE;




    public function checkInputIntegrity()
    {
        $this->checkIfSetName();
    }


}
