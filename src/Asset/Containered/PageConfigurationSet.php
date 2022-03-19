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


    public function setNewAsset(\stdClass $assetData)
    {

        try {
            $this->checkDependencies($assetData);
            parent::setNewAsset($assetData);
        }catch (InputIntegrityException $e){
            $msg = $e->getMessage() . PHP_EOL;
            $msg .= "Task aborted." . PHP_EOL;
            die($msg);
        }catch (AssetNotFoundException $e){
            $msg = $e->getMessage() . PHP_EOL;
            $msg .= "Task aborted." . PHP_EOL;
            die($msg);
        }

    }

    public function checkDependencies(\stdClass $assetData)
    {
        $configurations = $assetData->pageConfigurations['pageConfiguration'];
        foreach ($configurations as $index => $config){
            $this->checkConfigurationIntegrity($config, $index);
            $this->checkExistenceTemplate($config['templatePath']);
        }
    }

    public function checkExistenceTemplate($templatePath): bool
    {
        $template = new Template($this->wcms);
        if(!$template->assetExists($templatePath)){
            $msg = $template->assetTypeDisplay;
            $msg .= ": " . $templatePath;
            $msg .= " doesn't exist";
            throw new AssetNotFoundException($msg);
        }
    }

    public function checkConfigurationIntegrity(array $config, int $index)
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
            if(!isset($config[$k])){
                throw new InputIntegrityException("For Output(page configuration) #$index, [$k] is required, and its value should be: $v");
            }
        }
    }

    public function checkInputIntegrity(\stdClass $assetData)
    {
        parent::checkInputIntegrity($assetData);
        $this->checkIfSetPageConfigurations($assetData);

    }

    public function checkIfSetPageConfigurations(\stdClass $assetData)
    {
        $pageConfigurationsExist = isset($assetData->pageConfigurations['pageConfiguration']);

        if(!$pageConfigurationsExist){
            $msg = "Missing inputs of pageConfigurations. Please add one by example: ";
            $msg .= PHP_EOL;
            $msg .= $this->pageConfigurationsExample;
            throw new InputIntegrityException($msg);
        }
    }



}