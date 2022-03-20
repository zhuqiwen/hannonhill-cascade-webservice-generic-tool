<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;



use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

trait AssetTrait
{

    protected $wcms;
    protected $namespace;
    public $oldAsset;
    public $newAsset;
    public $assetTypeDisplay;
    public $assetTypeFetch;
    public $assetTypeCreate;





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
        try {
            $this->checkInputIntegrity($assetData);
            $this->checkDependencies($assetData);
            $this->newAsset = $assetData;
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

    public function assetExists(string $path): bool
    {
        return (bool)$this->wcms->assetExists($path, $this->assetTypeFetch);
    }


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

        $path = DIRECTORY_SEPARATOR
            . trim($parentPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . trim($this->newAsset->name);

        return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
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

    public function checkInputIntegrity(\stdClass $assetData)
    {
        $this->checkIfSetName($assetData);
    }

    public function checkIfSetName(\stdClass $assetData)
    {
        $className = $this->getClassName();

        if(!isset($assetData->name)){
            throw new InputIntegrityException("$className payload: [name] => 'ASSET-NAME' is missing");
        }
    }

    public function checkDependencies(\stdClass $assetData)
    {

    }

    public function getClassName(): string
    {
        $array = explode('\\', get_called_class());

        return array_pop($array);
    }

    public function checkExistenceAndThrowException(Asset $asset, string $path)
    {
        if(!$asset->assetExists($path)){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= $asset->assetTypeDisplay;
            $msg .= ": " . $path;
            $msg .= " doesn't exist";
            throw new AssetNotFoundException($msg);
        }
    }
}