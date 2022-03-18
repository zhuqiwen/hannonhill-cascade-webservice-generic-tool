<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;



use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;

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
            throw new AssetNotFoundException($this->assetTypeDisplay . ": " . $path . " could not be found");
        }
    }

    public function setNewAsset(\stdClass $assetData)
    {
        $this->newAsset = $assetData;
    }

    public function assetExists(string $path): bool
    {
        return (bool)$this->wcms->assetExists($path, $this->assetTypeFetch);
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


        $calledClass = get_called_class();

        $parentContainerKey = strpos($calledClass, 'Foldered') === false ? 'parentContainerPath' : 'parentFolderPath';

        $parentAsset = (object) [
            $parentContainerKey => $grantParentPath,
            'name' => $parentName,
            'path' => str_replace("//", "/", $grantParentPath . "/" . $parentName),
        ];

        return compact("path", "grantParentPath", "parentAsset");

    }


//    public function createAsset()
//    {
//
//        if(!$this->wcms->assetExists($this->getParentPathForCreate(), $this->assetTypeFetch))
//        {
//            $this->createParent();
//        }
//
//        unset($this->newAsset->path);
//        $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);
//    }
//
//    public function createParent()
//    {
//        $data = $this->prepareParentAssetForCreate();
//        $parentAsset = $data['parentAsset'];
//
//
//        if($parentAsset->path != DIRECTORY_SEPARATOR)
//        {
//            $fullCallerParentClass = get_parent_class(get_called_class());
//
//            $folder = new $fullCallerParentClass($this->wcms);
//            $folder->setNewAsset($parentAsset);
//            $folder->createAsset();
//        }
//    }



    public function getParentPathForCreate()
    {
        $calledClass = get_called_class();

        if(strpos($calledClass, "Foldered") !== false)
        {
            $path = $this->newAsset->parentFolderPath;
        }
        elseif(strpos($calledClass, "Containered") !== false)
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

    public function getNewAssetPath()
    {
        $parentPath = $this->getParentPathForCreate();

        return DIRECTORY_SEPARATOR
            . trim($parentPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . trim($this->newAsset->name);

    }

    public function createAsset()
    {
        $parentClass = get_parent_class(get_called_class());
        $parentPath = $this->getParentPathForCreate();
        try {
            new $parentClass($this->wcms, $parentPath);
        }catch (AssetNotFoundException $e){
            echo $e->getMessage() . ", which will be created now." . PHP_EOL;
            $this->createParent();
        }catch (\RuntimeException $e){
            echo $e->getMessage();
        }

        if(!$this->assetExists($this->getNewAssetPath()))
        {
            unset($this->newAsset->path);
            $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);
            echo "The following asset has been created:" . PHP_EOL;
            print_r($this->newAsset);
        }

    }

    /**
     *
     * Directs parent creation to either Folder or other container classes
     * Folder and other container classes have overrides
     *
     */
    public function createParent()
    {
        $parentClass = get_parent_class(get_called_class());
        $parentAsset = $this->getParentAssetForCreate();
        $parent = new $parentClass($this->wcms);
        $parent->setNewAsset($parentAsset);
        $parent->createAsset();
    }

    public function getParentAssetForCreate(): \stdClass
    {
        $calledClass = get_called_class();
        $parentContainerKey = strpos($calledClass, 'Foldered') === false ? 'parentContainerPath' : 'parentFolderPath';

        $path = explode(DIRECTORY_SEPARATOR, $this->getParentPathForCreate());
        $name = array_pop($path);

        return (object)[
            $parentContainerKey => implode(DIRECTORY_SEPARATOR, $path),
            'name' => $name
        ];

    }
}