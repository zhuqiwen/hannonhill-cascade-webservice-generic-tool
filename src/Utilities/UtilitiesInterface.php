<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

interface UtilitiesInterface{
    public function getAllInSite(string $siteName = '', string $apiKey = ''):array;

    public function getAllInContainer(string $containerOrFolderPath):array;
}