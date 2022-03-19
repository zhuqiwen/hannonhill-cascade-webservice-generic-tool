<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;

class WysiwygEditorConfiguration extends Asset{

    public  $assetTypeDisplay = "WYSIWYG Editor Configuration";
    public  $assetTypeFetch = ASSET_EDITOR_CONFIGURATION_FETCH;
    public  $assetTypeCreate = ASSET_EDITOR_CONFIGURATION_CREATE;




    public function checkInputIntegrity()
    {
        $this->checkIfSetName();
    }


}
