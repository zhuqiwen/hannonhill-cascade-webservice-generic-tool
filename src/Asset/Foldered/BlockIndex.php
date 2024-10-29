<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;


use Edu\IU\Framework\GenericUpdater\Asset\Containered\DataDefinition;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

class BlockIndex extends Block {
    protected $assetTypeDisplay = "Block";
    protected $assetTypeFetch = ASSET_BLOCK_INDEX_FETCH;
    protected $assetTypeCreate = ASSET_BLOCK_INDEX_CREATE;


    public array $allowedIndexType = ['folder', 'content-type'];

    public array $allowedRenderingBehavior = [
        'render-normally',
        'hierarchy',
        'hierarchy-with-siblings',
        'hierarchy-siblings-forward'
        ];

    public array $allowedSortMethod = [
        'alphabetical',
        'folder-order',
        'last-modified-date',
        'created-date'
    ];

    public array $allowedSortOrder = ['ascending', 'descending'];

    public array $allowedPageXMLRendering = [
        'no-render',
        'render',
        'render-current-page-only'
    ];


    public function setNewAsset(\stdClass $assetData): void
    {

        if (!isset($assetData->maxRenderedAssets)){
            $assetData->maxRenderedAssets = 0;
        }

        if (!isset($assetData->depthOfIndex)){
            $assetData->depthOfIndex = 0;
        }

        parent::setNewAsset($assetData);
    }
}