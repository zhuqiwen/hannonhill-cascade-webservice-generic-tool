<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;



trait AssetTrait
{

    protected $wcms;
    protected $namespace;
    public $oldAsset;
    public $newAsset;
    public $assetTypeDisplay;
    public $assetTypeFetch;
    public $assetTypeCreate;
    public $containerClassName;





    public function setOldAsset(string $path)
    {
        if($this->assetExists($path))
        {
            $asset = $this->wcms->fetchAsset($path, $this->assetTypeFetch);
            $this->oldAsset = $asset->{$this->assetTypeCreate};
        }
        else
        {
            //TODO: set error and throw exception for user api key
        }
    }

    public function setNewAsset(\stdClass $assetData)
    {
        $this->newAsset = $assetData;
    }

    public function assetExists(string $path): bool
    {
        return $this->wcms->assetExists($path, $this->assetTypeFetch);
    }

    public function updateAsset()
    {
        $this->wcms->saveAsset($this->newAsset, $this->assetTypeCreate);
    }

    public function rollbackUpdateAsset()
    {
        $this->wcms->saveAsset($this->oldAsset, $this->assetTypeCreate);
    }









    public function rollbackCreateAsset(){
        $this->deleteAsset();
    }

    public function deleteAsset()
    {
        if($this->wcms->assetExists($this->oldAsset->path, $this->assetTypeFetch))
        {
            $this->wcms->deleteAsset($this->assetTypeFetch, $this->oldAsset->path);
        }
    }

    public function rollbackDeleteAsset()
    {

    }

    public function prepareParentAssetForCreate(): array
    {
        $path = $this->getNewAssetPath();
        $pathArray = explode(DIRECTORY_SEPARATOR, $path);
        // remove current folder name
        array_pop($pathArray);
        $parentName = array_pop($pathArray);
        $grantParentPath = implode(DIRECTORY_SEPARATOR, $pathArray);
        $grantParentPath = empty($grantParentPath) ? DIRECTORY_SEPARATOR : $grantParentPath;



        $parentContainerKey = strpos($this->containerClassName, 'Folder') === false ? 'parentContainerPath' : 'parentFolderPath';

        $parentAsset = (object) [
            $parentContainerKey => $grantParentPath,
            'name' => $parentName,
            'path' => str_replace("//", "/", $grantParentPath . "/" . $parentName),
        ];

        return compact("path", "grantParentPath", "parentAsset");

    }


    public function createAsset()
    {
        if(!$this->wcms->assetExists($this->getParentPathForCreate(), $this->assetTypeFetch))
        {
            $this->createParent();
        }

        unset($this->newAsset->path);
        $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);

    }

    public function createParent()
    {
        $data = $this->prepareParentAssetForCreate();
        $parentAsset = $data['parentAsset'];


        if($parentAsset->path != DIRECTORY_SEPARATOR)
        {
            $containerClassName = $this->getNamespace() . '\\' . $this->containerClassName;
            $folder = new $containerClassName($this->wcms);
            $folder->setNewAsset($parentAsset);

            $folder->createAsset();
        }
    }


    protected function getNamespace(){
        return "Edu\IU\Framework\GenericUpdater\Asset";
    }

    public function getParentPathForCreate()
    {
        $classNames = $this->getCalledClassAndParentClass();
        extract($classNames);


        if($callerParentClassName == "FolderContainedAsset")
        {
            $path = $this->newAsset->parentFolderPath;
        }
        elseif($callerParentClassName == "ContaineredAsset")
        {
            $path = $this->newAsset->parentContainerPath;
        }
        else
        {
            $msg = $calledClass;
            $msg .= " needs to be child of either FolderContainedAsset or ContaineredAsset to be able to create parent folder or container.";
            throw new \RuntimeException($msg);
        }

        return $path;
    }

    public function getCalledClassAndParentClass()
    {
        $calledClass = get_called_class();
        $fullCallerParentClass = get_parent_class($calledClass);
        $fullCallerParentClass = explode('\\', $fullCallerParentClass);
        $callerParentClassName = array_pop($fullCallerParentClass);

        return compact('calledClass', 'callerParentClassName');
    }

    public function getNewAssetPath()
    {
        $parentPath = $this->getParentPathForCreate();

        $path = DIRECTORY_SEPARATOR
            . trim($parentPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . trim($this->newAsset->name);

        return $path;
    }
}