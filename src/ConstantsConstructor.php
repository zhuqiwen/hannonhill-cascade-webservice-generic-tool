<?php

namespace Edu\IU\Framework\GenericUpdater;

class ConstantsConstructor
{
    public function __construct(string $siteName, array $extra = [])
    {
        $siteName = trim($siteName);
        define("CLIENT_SITE_NAME", $siteName);
        define("WSDL", "https://sites.wcms.iu.edu/ws/services/AssetOperationService?wsdl");
        define("WSDL_BUILDER", "https://builder.wcms.iu.edu/ws/services/AssetOperationService?wsdl");
        define("DEBUG_MODE", true);
        define("ROOT_CLASS_NAME", "Edu\IU\Framework\GenericUpdater\Asset\Asset");
        define("REGEX_FOR_SITE_NAME_IN_PATH", "/site:\/\/([^\/]*)\//i");



        $this->defineAssetTypesForCreate();
        $this->defineAssetTypesForFetch();
        $this->defineHtmlEntities();

        $this->defineExtra($extra);

    }

    private function defineExtra(array $extra)
    {
        if(!empty($extra))
        {
            foreach ($extra as $constantName => $constantVal)
            {
                $constantName = strtoupper($constantName);
                if(defined($constantName))
                {
                    throw new \RuntimeException("$constantName already defined");
                }
                else
                {
                    define($constantName, $constantVal);
                }
            }
        }

    }

    private function defineAssetTypesForFetch()
    {
        define("ASSET_FILE_FETCH", "file");
        define("ASSET_FOLDER_FETCH", "folder");
        define("ASSET_FORMAT_FETCH", "format");
        define("ASSET_FORMAT_XSLT_FETCH", "format_XSLT");
        define("ASSET_FORMAT_SCRIPT_FETCH", "format_SCRIPT");
        define("ASSET_BLOCK_FETCH", "block");
        define("ASSET_BLOCK_FEED_FETCH", "block_FEED");
        define("ASSET_BLOCK_INDEX_FETCH", "block_INDEX");
        define("ASSET_BLOCK_TEXT_FETCH", "block_TEXT");
        define("ASSET_BLOCK_XHTML_FETCH", "block_XHTML_DATADEFINITION");
        define("ASSET_BLOCK_XML_FETCH", "block_XML");
        define("ASSET_SHARED_FIELD_FETCH", "sharedfield");
        define("ASSET_DATA_DEFINITION_FETCH", "datadefinition");
        define("ASSET_CONTENT_TYPE_FETCH", "contenttype");
        define("ASSET_ASSET_FACTORY_FETCH", "assetfactory");
        define("ASSET_METADATA_SET_FETCH", "metadataset");
        define("ASSET_PAGE_CONFIGURATION_SET_FETCH", "pageconfigurationset");
        define("ASSET_PAGE_FETCH", "page");
        define("ASSET_TEMPLATE_FETCH", "template");
        define("ASSET_SYMLINK_FETCH", "symlink");
        define("ASSET_EDITOR_CONFIGURATION_FETCH", "editorconfiguration");

        define("ASSET_CONTAINER_PAGE_CONFIGURATION_SET_FETCH", "pageconfigurationsetcontainer");
        define("ASSET_CONTAINER_SHARED_FIELD_FETCH", "sharedfieldcontainer");
        define("ASSET_CONTAINER_DATA_DEFINITION_FETCH", "datadefinitioncontainer");
        define("ASSET_CONTAINER_CONTENT_TYPE_FETCH", "contenttypecontainer");
        define("ASSET_CONTAINER_METADATA_SET_FETCH", "metadatasetcontainer");
        define("ASSET_CONTAINER_ASSET_FACTORY_FETCH", "assetfactorycontainer");
    }

    private function defineAssetTypesForCreate()
    {
        define("ASSET_FILE_CREATE", "file");
        define("ASSET_FOLDER_CREATE", "folder");
        define("ASSET_PAGE_CREATE", "page");
        define("ASSET_FORMAT_CREATE", "format");
        define("ASSET_BLOCK_CREATE", "block");
        define("ASSET_BLOCK_FEED_CREATE", "feedBlock");
        define("ASSET_BLOCK_INDEX_CREATE", "indexBlock");
        define("ASSET_BLOCK_TEXT_CREATE", "textBlock");
        define("ASSET_BLOCK_XHTML_CREATE", "xhtmlDataDefinitionBlock");
        define("ASSET_BLOCK_XML_CREATE", "xmlBlock");
        define("ASSET_REFERENCE_CREATE", "reference");
        define("ASSET_FORMAT_SCRIPT_CREATE", "scriptFormat");
        define("ASSET_FORMAT_XSLT_CREATE", "xsltFormat");
        define("ASSET_SYMLINK_CREATE", "symlink");
        define("ASSET_TEMPLATE_CREATE", "template");
        define("ASSET_EDITOR_CONFIGURATION_CREATE", "editorConfiguration");



        define("ASSET_SHARED_FIELD_CREATE", "sharedField");
        define("ASSET_DATA_DEFINITION_CREATE", "dataDefinition");
        define("ASSET_CONTENT_TYPE_CREATE", "contentType");
        define("ASSET_ASSET_FACTORY_CREATE", "assetFactory");
        define("ASSET_METADATA_SET_CREATE", "metadataSet");
        define("ASSET_PAGE_CONFIGURATION_SET_CREATE", "pageConfigurationSet");



        define("ASSET_CONTAINER_PAGE_CONFIGURATION_SET_CREATE", "pageConfigurationSetContainer");
        define("ASSET_CONTAINER_SHARED_FIELD_CREATE", "sharedFieldContainer");
        define("ASSET_CONTAINER_DATA_DEFINITION_CREATE", "dataDefinitionContainer");
        define("ASSET_CONTAINER_CONTENT_TYPE_CREATE", "contentTypeContainer");
        define("ASSET_CONTAINER_METADATA_SET_CREATE", "metadataSetContainer");
        define("ASSET_CONTAINER_ASSET_FACTORY_CREATE", "assetFactoryContainer");
    }




    private function defineHtmlEntities()
    {
        define("HTML_CHECK_MARK", '&#10004;');
        define("HTML_BALLOT", '&#10007;');
    }


}