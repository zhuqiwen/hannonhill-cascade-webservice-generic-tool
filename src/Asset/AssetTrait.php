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
        $this->newAsset = $assetData;

        try {
            $this->checkInputIntegrity();
            $this->checkDependencies();
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

    public function deleteAsset(string $path = "")
    {
        if(!isset($this->oldAsset->path) && empty(trim($path))){
            $msg = "Path to the asset of " . $this->assetTypeDisplay . " must be provided either as #1 paremeter for deleteAsset() ";
            $msg .= " or as #2 parameter for the object constructor.";
            die($this->echoForCLI($msg));
        }

        $path = empty(trim($path)) ? $this->oldAsset->path : $path;

        if ($this->assetExists($path)){
            $this->wcms->deleteAsset($this->assetTypeFetch, $path);
            $msg = "Asset: " . $this->assetTypeDisplay;
            $msg .= " with path: " . $this->oldAsset->path;
            $msg .= " has been deleted successfully." . PHP_EOL;
            $this->echoForCLI($msg);
        }

        // pause for 3 seconds for the WCMS database to update status after each deletion in a batch
        sleep(3);
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

    public function getNewAssetPath(): string
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

    public function checkInputIntegrity()
    {
        $this->checkIfSetName();
    }

    public function checkIfSetName()
    {
        $className = $this->getClassName();

        if(!isset($this->newAsset->name)){
            throw new InputIntegrityException("$className payload: [name] => 'ASSET-NAME' is missing");
        }
    }

    public function checkDependencies()
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


    public function getChildClassesOf(string $parentClassString): array
    {
        $result = [];
        foreach (get_declared_classes() as $class){
            if(is_subclass_of($class, $parentClassString)){
                $array = explode('\\', $class);
                $assetType = array_pop($array);
                $result[strtolower($assetType)] = $class;
            }
        }

        return $result;
    }

    public function isValidXML(string $xml): array
    {
        $prev = libxml_use_internal_errors(true);

        $doc = simplexml_load_string($xml);
        $xmlErrors = libxml_get_errors();

        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $isValidXML =  false !== $doc && empty($errors);

        return compact('isValidXML', 'xmlErrors');
    }

    public function checkIfSetXML()
    {
        $checkResult = $this->isValidXML($this->newAsset->xml);
        extract($checkResult);

        if(!isset($this->newAsset->xml) || empty(trim($this->newAsset->xml))){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", [xml] is required. Please add one by example: ";
            throw new InputIntegrityException($msg);
        }

        if(!$isValidXML){
            $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= ", the value of [xml] should be a valid xml." .PHP_EOL;
            $msg .= "Errors: " . PHP_EOL;
            $msg .= "\t" . print_r($xmlErrors);
            throw new InputIntegrityException($msg);
        }
    }

    protected function echoForCLI(string $msg)
    {
        if(php_sapi_name() == "cli"){
            echo $msg . PHP_EOL;
        }
    }

}