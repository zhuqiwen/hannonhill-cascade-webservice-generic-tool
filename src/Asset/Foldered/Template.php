<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class Template extends Folder {
    public $assetTypeDisplay = "Template";
    public $assetTypeFetch = ASSET_TEMPLATE_FETCH;
    public $assetTypeCreate = ASSET_TEMPLATE_CREATE;


    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetXML($assetData);
    }

    public function checkIfSetXML(\stdClass $asset)
    {
        [$isValidXML, $xmlErrors] = $this->isValidXML($asset->xml);

        if(!isset($asset->xml) || empty(trim($asset->xml))){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [xml] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }

        if(!$isValidXML){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", the value of [xml] should be a valid xml." .PHP_EOL;
            $msg .= "Errors: " . PHP_EOL;
            $msg .= "\t" . print_r($xmlErrors);
            throw new InputIntegrityException($msg);
        }
    }

    public function isValidXML(string $xml): array
    {
        $prev = libxml_use_internal_errors(true);

        $doc = simplexml_load_string($xml);
        $errors = libxml_get_errors();

        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $result =  false !== $doc && empty($errors);

        return compact('result', 'errors');
    }
}
