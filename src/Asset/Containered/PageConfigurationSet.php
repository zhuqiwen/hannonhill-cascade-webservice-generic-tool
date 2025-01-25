<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Foldered\Template;
use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class PageConfigurationSet extends PageConfigurationSetContainer {
    protected $assetTypeDisplay = "Page Configuration Set";
    protected $assetTypeFetch = ASSET_PAGE_CONFIGURATION_SET_FETCH;
    protected $assetTypeCreate = ASSET_PAGE_CONFIGURATION_SET_CREATE;

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


    /**
     * get $asset->pageConfigurations->pageConfiguration
     * if there is only one pageConfiguration, then put it in a array
     * @return array
     */
    public function getPageConfigurations(): array
    {
        $result = $this->getOldAsset()->pageConfigurations->pageConfiguration;
        if(!is_array($result) && $result instanceof \stdClass){
            $result = [$result];
        }

        return $result;
    }


    public function getPageConfigurationByName(string $pageConfigurationName):\stdClass | null
    {
        $result = null;
        $pageConfigurations = $this->getPageConfigurations();
        foreach ($pageConfigurations as $pageConfiguration){
            if($pageConfiguration->name == $pageConfigurationName){
                $result = $pageConfiguration;
            }
        }

        return $result;
    }

    /**
     * get an array of $asset->pageConfigurations->pageConfiguration by checking $asset->pageConfigurations->pageConfiguration->templatePath;
     * if none, return empty array
     * @param string $templatePath
     * @return array
     */
    public function getPageConfigurationsByTemplatePath(string $templatePath): array
    {
        $result = [];
        $pageConfigurations = $this->getPageConfigurations();
        foreach ($pageConfigurations as $pageConfiguration){
            if($pageConfiguration->templatePath == $templatePath){
                $result[] = $pageConfiguration;
            }
        }
    }

    /**
     * Retrieve page configurations filtered by the specified output extension.
     *
     *  get an array of $asset->pageConfigurations->pageConfiguration by checking $asset->pageConfigurations->pageConfiguration->outputExtension;
     *  if none, return empty array
     * @param string $outputExtension The output extension to filter page configurations by.
     * @return array An array of page configurations matching the given output extension.
     */
    public function getPageConfigurationsByOutputExtension(string $outputExtension): array
    {
        $result = [];
        $pageConfigurations = $this->getPageConfigurations();
        foreach ($pageConfigurations as $pageConfiguration){
            if($pageConfiguration->outputExtension == $outputExtension){
                $result[] = $pageConfiguration;
            }
        }

        return $result;
    }

    /**
     * get an array of $asset->pageConfigurations->pageConfiguration by checking $asset->pageConfigurations->pageConfiguration->serializationType;
     * if none, return empty array
     *
     * @param string $serializationType
     * @return array
     */
    public function getPageConfigurationsBySerializationType(string $serializationType): array
    {
        $result = [];
        $pageConfigurations = $this->getPageConfigurations();
        foreach ($pageConfigurations as $pageConfiguration){
            if($pageConfiguration->serializationType == $serializationType){
                $result[] = $pageConfiguration;
            }
        }

        return $result;

    }

    /**
     *
     * get an array of $asset->pageConfigurations->pageConfiguration
     * by checking if $asset->pageConfigurations->pageConfiguration->publishable is true;
     *  if none, return empty array
     * @return array
     */
    public function getPublishablePageConfigurations(): array
    {
        $result = [];
        $pageConfigurations = $this->getPageConfigurations();
        foreach ($pageConfigurations as $pageConfiguration){
            if($pageConfiguration->publishable == '1'){
                $result[] = $pageConfiguration;
            }
        }

        return $result;
    }

    /**
     * get the default pageConfiguration
     * @return \stdClass|null
     */
    public function getDefaultPageConfiguration(): \stdClass | null
    {
        $result = null;
        $pageConfigurations = $this->getPageConfigurations();
        foreach ($pageConfigurations as $pageConfiguration){
            if($pageConfiguration->defaultConfiguration == '1'){
                $result = $pageConfiguration;
            }
        }

        return $result;

    }

}