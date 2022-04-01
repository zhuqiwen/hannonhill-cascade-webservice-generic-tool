<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Containered;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;
use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;

trait ContaineredAssetTrait {

    public function createAsset(): Asset
    {
        $parentClass = $this->getParentClass();
        try {
            new $parentClass($this->wcms, $this->newAsset->parentContainerPath);
        }catch (AssetNotFoundException $e){
            $msg = $e->getMessage() . ", which will be created now." . PHP_EOL;
            $this->echoForCLI($msg);
            $this->createParent();
        }catch (\RuntimeException $e){
            $this->echoForCLI($e->getMessage());
        }

        if(!$this->assetExists($this->getNewAssetPath()))
        {
            unset($this->newAsset->path);
            $result = $this->wcms->createAsset($this->assetTypeCreate, $this->newAsset);
            $msg = "The following Asset has been created:" . PHP_EOL;
            $msg .= "\tAsset Type: " . $this->assetTypeDisplay . PHP_EOL;
            $msg .= "\tAsset Path: " . $this->getNewAssetPath() . PHP_EOL;
            $this->echoForCLI($msg);

            $this->newAsset->id = $result->createReturn->createdAssetId;
        }
        else
        {
            //if oldAsset is set, nothing new created
            //if not, a new asset is created
            $this->setOldAsset($this->getNewAssetPath());
        }

        return $this;
    }

    public function createParent()
    {
        $asset = $this->getGrantParentAssetForCreate();
        $path = $asset->parentContainerPath . DIRECTORY_SEPARATOR . $asset->name;

        if ($path == DIRECTORY_SEPARATOR)
        {
            return;
        }

        if(!$this->wcms->assetExists($path, $this->assetTypeFetch))
        {
            $parentClass = $this->getParentClass();
            $folder = new $parentClass($this->wcms);
            $folder->setNewAsset($asset);
            $folder->createAsset();
            //set parent container just created
            $assetInfo = (object)[
                'class' => $parentClass,
                'data' => $asset
            ];
            $folder->putContainerCreated($assetInfo);
            //pass parent container info to child
            $this->setContainersCreatedOnTheWay(
                array_merge(
                    $this->getContainersCreatedOnTheWay(),
                    $folder->getContainersCreatedOnTheWay()
                )
            );
        }


    }

    public function getGrantParentAssetForCreate(): \stdClass
    {
        $array = explode(DIRECTORY_SEPARATOR, $this->newAsset->parentContainerPath);
        $grantParentName = array_pop($array);
        $grantParentPath = implode(DIRECTORY_SEPARATOR, $array);

        return (object)[
            'parentContainerPath' => empty($grantParentPath) ? '/' : $grantParentPath,
            'name' => $grantParentName
        ];

    }

    public function getParentClass(): string
    {
        $parentClass = get_parent_class(get_called_class());
        if($parentClass == ROOT_CLASS_NAME)
        {
            $parentClass = get_called_class();
        }

        return $parentClass;
    }


    public function checkInputIntegrity()
    {
        $this->checkIfSetParentPath();
        $this->checkIfSetName();
    }

    public function checkIfSetParentPath()
    {
        $className = $this->getClassName();

        if(!isset($this->newAsset->parentContainerPath)){
            throw new InputIntegrityException("$className payload: [parentContainerPath] => 'PATH-TO-PARENT' is missing");
        }
    }


    protected function echoForCLI(string $msg)
    {
        if(php_sapi_name() == "cli"){
            echo $msg . PHP_EOL;
        }
    }
}