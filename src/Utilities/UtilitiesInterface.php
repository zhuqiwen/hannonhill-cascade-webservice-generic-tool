<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

interface UtilitiesInterface{
    public function getAllInSite(string $siteName = '', string $apiKey = ''):array;


    /**
     * get all asset info such as id, path, siteId, siteName, and type
     * @param string $containerOrFolderPath
     * @return array
     */
    public function getAllInContainer(string $containerOrFolderPath):array;
}