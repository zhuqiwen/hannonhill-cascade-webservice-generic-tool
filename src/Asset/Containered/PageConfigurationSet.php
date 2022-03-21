<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Foldered\Template;
use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class PageConfigurationSet extends PageConfigurationSetContainer {
    public $assetTypeDisplay = "Page Configuration Set";
    public $assetTypeFetch = ASSET_PAGE_CONFIGURATION_SET_FETCH;
    public $assetTypeCreate = ASSET_PAGE_CONFIGURATION_SET_CREATE;

    public $pageConfigurationsExample = <<< PAGECONFIGEXAMPLE
[
    'pageConfigurations' => [
        'pageConfiguration' => [
            [
                'name' => 'NAME-OF-OUTPUT',
                'defaultConfiguration' => true or false,
                'templatePath' => 'PATH-TO-TEMPLATE',
                'outputExtension' => '.html' or other desired extensions,
                'serializationType' => 'HTML' or other supported types: XML, CSS, JS, JSON, or PDF
            ]
        ]
    ]
]
PAGECONFIGEXAMPLE;


    public function checkDependencies()
    {
        $configurations = $this->newAsset->pageConfigurations->pageConfiguration;
        foreach ($configurations as $index => $config){
            $this->checkConfigurationIntegrity($config, $index);
            $this->checkExistenceTemplate($config->templatePath);
        }
    }

    public function checkExistenceTemplate($templatePath)
    {
        $template = new Template($this->wcms);
        $this->checkExistenceAndThrowException($template, $templatePath);
    }

    public function checkConfigurationIntegrity(\stdClass $config, int $index)
    {
        $requiredKeys = [
            'name' => 'any string',
            'defaultConfiguration' => 'boolean',
            'templatePath' => 'a valid template path',
            'outputExtension' => '.html or other desired extensions',
            'serializationType' => 'HTML, XML, CSS, JS, JSON, or PDF',
        ];
        foreach ($requiredKeys as $k => $v)
        {
            if(!isset($config->$k)){
                $msg = "In the payload of Page Configuration Set, ";
                $msg .= "[pageConfigurations][pageConfiguration][$index]";
                $msg .= "[$k] is required, and its value should be: $v";
                throw new InputIntegrityException($msg);
            }
        }
    }

    public function checkInputIntegrity()
    {
        parent::checkInputIntegrity();
        $this->checkIfSetPageConfigurations();

    }

    public function checkIfSetPageConfigurations()
    {
        $pageConfigurationsExist = isset($this->newAsset->pageConfigurations->pageConfiguration);

        if(!$pageConfigurationsExist){
            $msg = "Missing inputs of pageConfigurations. Please add one by example: ";
            $msg .= PHP_EOL;
            $msg .= $this->pageConfigurationsExample;
            throw new InputIntegrityException($msg);
        }
    }



}