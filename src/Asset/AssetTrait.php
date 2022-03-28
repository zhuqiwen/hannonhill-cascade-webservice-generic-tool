<?php

namespace Edu\IU\Framework\GenericUpdater\Asset;



use Edu\IU\Framework\GenericUpdater\Asset\Foldered\Folder;
use Edu\IU\Framework\GenericUpdater\Exception\APIKeyException;
use Edu\IU\Framework\GenericUpdater\Exception\AssetNotFoundException;
use Edu\IU\Framework\GenericUpdater\Exception\InputIntegrityException;
use Edu\IU\Framework\GenericUpdater\Step;
use phpDocumentor\Reflection\Types\This;

trait AssetTrait
{

    protected $wcms;
    protected $oldAsset;
    protected $newAsset;
    protected $siteName;
    protected $assetTypeDisplay;
    protected $assetTypeFetch;
    protected $assetTypeCreate;
    protected $containersCreatedOnTheWay = [];


    /**
     * @return mixed
     */
    public function getAssetTypeCreate()
    {
        return $this->assetTypeCreate;
    }

    /**
     * @return mixed
     */
    public function getAssetTypeFetch()
    {
        return $this->assetTypeFetch;
    }

    /**
     * @return mixed
     */
    public function getAssetTypeDisplay()
    {
        return $this->assetTypeDisplay;
    }

    /**
     * @return mixed
     */
    public function getOldAsset(): \stdClass
    {
        if(!isset($this->oldAsset) || empty($this->oldAsset)){
            $msg = "For " . $this->assetTypeDisplay;
            $msg .= " oldAsset is not set.";
            throw new \RuntimeException($msg);
        }
        return clone $this->oldAsset;
    }

    /**
     * @return mixed
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * @return mixed
     */
    public function getNewAsset()
    {
        if(!isset($this->newAsset))
        {
            throw new RuntimeException("newAsset has not been set");
        }

        return clone $this->newAsset;
    }

    /**
     * @return array
     */
    public function getContainersCreatedOnTheWay(): array
    {
        return $this->containersCreatedOnTheWay;
    }

    /**
     * @param array $containersCreatedOnTheWay
     */
    public function setContainersCreatedOnTheWay(array $containersCreatedOnTheWay): void
    {
        $this->containersCreatedOnTheWay = $containersCreatedOnTheWay;
    }

    public function putContainerCreated(\stdClass $parentAsset)
    {
        $this->containersCreatedOnTheWay[] = $parentAsset;
    }


    public function setOldAsset(string $path)
    {
        try {
            $asset = $this->wcms->fetchAsset($path, $this->assetTypeFetch);
            $this->oldAsset = $asset->{$this->assetTypeCreate};
        }catch (\RuntimeException $e){
            if (strpos($e->getMessage(), "Unable to identify an entity") !== false){
                $msg = $this->assetTypeDisplay . ": " . $path;
                $msg .= " could not be found in Site: " . $this->siteName;
                throw new AssetNotFoundException($msg);
            }
            if (strpos($e->getMessage(), "A valid API Key must be provided") !== false){
                $msg = "A valid API Key must be provided to access assets in Site: " . $this->siteName;
                throw new APIKeyException($msg);
            }
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


    public function createAsset(): Asset
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

        return $this;
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
            $msg .= " with path: " . $path;
            $msg .= " has been deleted successfully." . PHP_EOL;
            $this->echoForCLI($msg);
        }

        // pause for 3 seconds for the WCMS database to update status after each deletion in a batch
        sleep(3);
    }

    public function updateAsset(): Asset
    {

        if(!isset($this->newAsset)){
            $msg = "For " . $this->assetTypeDisplay;
            $msg .= " newAsset is not set. Please call \$asset->setNewAsset(\$assetData) to set it.";
            throw new \RuntimeException($msg);
        }

        if(!$this->assetExists($this->getNewAssetPath())){
            $msg = "Asset: " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
            $msg .= " doesn't exist. Update task could not be performed.";
            throw new \RuntimeException($msg);
        }

        $this->wcms->saveAsset($this->newAsset, $this->assetTypeCreate);
        $msg = "Asset: " . $this->assetTypeDisplay . " with path: " . $this->newAsset->path;
        $msg .= " has been updated successfully";
        $this->echoForCLI($msg);

        return $this;
    }

    /**
     * ROLLBACKs
     */

    public function rollbackUpdateAsset()
    {
        if(!isset($this->oldAsset)){
            $msg = "For " . $this->assetTypeDisplay;
            $msg .= " oldAsset is not set. Please call \$asset->setOldAsset(\$assetPath) to set it.";
            throw new \RuntimeException($msg);
        }

        $this->wcms->saveAsset($this->oldAsset, $this->assetTypeCreate);
        $msg = "Asset: " . $this->assetTypeDisplay . " with path: " . $this->oldAsset->path;
        $msg .= " has been restored successfully";
        $this->echoForCLI($msg);

    }

    public function rollbackCreateAsset()
    {
        $msg = "deleting (rolling back of creation): ";

        if(empty($this->containersCreatedOnTheWay)){
            $assetPath = $this->getParentPathForCreate() . DIRECTORY_SEPARATOR . $this->newAsset->name;
            $msg .=  $assetPath;
            $this->echoForCLI($msg);
            $this->deleteAsset($assetPath);
        }else{
            $msg .= $this->getTopAncesterPath();
            $this->echoForCLI($msg);
            //get parent class
            $top = $this->getTopAncesterCreated();
            $containerClass = $top->class;
            $topContainer = new $containerClass($this->wcms, $this->getTopAncesterPath());
            $topContainer->deleteAsset();
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

    public function getNewAssetPath(): string
    {
        if(isset($this->newAsset->path) && !empty($this->newAsset->path)){
            return $this->newAsset->path;
        }

        $parentPath = $this->getParentPathForCreate();

        $path = DIRECTORY_SEPARATOR
            . trim($parentPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . trim($this->newAsset->name);

        return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
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
        $msg = "For " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath();
        $msg .= ", " . $asset->assetTypeDisplay;
        $msg .= " with path: " . $path;

        if(!$asset->assetExists($path)){
            $msg .= " doesn't seem to exist in Site: " . $this->wcms->getSiteName() . PHP_EOL;
            $msg .= "\tOr the API Key doesn't have proper access to the site: " . $this->wcms->getSiteName() . PHP_EOL;
            $msg .= "\tOr the Site: " . $this->wcms->getSiteName() . " may not exist, and please check the site name carefully.";
            throw new AssetNotFoundException($msg);
        }
        $msg .= " exists in Site: " . $this->wcms->getSiteName();
        $this->echoForCLI($msg);
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

    protected function getTopAncesterCreated()
    {
        if (empty($this->containersCreatedOnTheWay)){
//            throw new \RuntimeException("containersCreatedOnTheWay is empty");
            return $this->containersCreatedOnTheWay;
        }

        return $this->getContainersCreatedOnTheWay()[0];
    }

    protected function getTopAncesterPath(): string
    {
        $top = $this->getTopAncesterCreated();
        $calledClass = get_called_class();

        if(strpos($calledClass, "Foldered") !== false)
        {
            $path = $top->data->parentFolderPath;
        }
        elseif(strpos($calledClass, "Containered") !== false)
        {
            $path = $top->data->parentContainerPath;
        }
        else
        {
            $msg = $calledClass;
            $msg .= " needs to be child of either FolderContainedAsset or ContaineredAsset to be able to create parent folder or container.";
            throw new \RuntimeException($msg);
        }

        $path = $path . DIRECTORY_SEPARATOR . $top->data->name;

        return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);

    }


    public function getSteps(string $action)
    {
        switch ($action){
            case 'update':
                $steps = $this->getUpdateSteps();
                break;
            case 'create':
                $steps = $this->getCreateSteps();
                break;
            default:
                if(empty(trim($action))){
                   $msg = '"action" as 2nd parameter of Action constructor should not be empty string. One of valid values: "create", and "update" should be used.';
                }else{
                    $msg = "'$action'" . " is not supported yet. 'create', or 'update' should be used as 2nd parameter of Action constructor";
                }

                throw new \RuntimeException($msg);
        }

        return $steps;
    }

    protected function getUpdateSteps()
    {
        $this->echoForCLI("collecting update steps for Asset: " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath());
        $steps = [
            new Step($this, 'updateAsset')
        ];

        return $steps;
    }

    protected function getCreateSteps()
    {
        $this->echoForCLI("collecting create steps for Asset: " . $this->assetTypeDisplay . " with path: " . $this->getNewAssetPath());

        $steps = [
            new Step($this, 'createAsset')
        ];

        return $steps;
    }

    protected function getSiteNameFromAssetPath(string $path)
    {
        preg_match(REGEX_FOR_SITE_NAME_IN_PATH, $path, $matches);
        $sitename = "";
        if(!empty($matches)){
            $sitename = $matches[1];
        }

        return $sitename;
    }

    protected function getOldAssetPath()
    {
        return $this->getOldAsset()->path;
    }

    protected function getRelationships()
    {
        return $this->wcms->listSubscribers($this->getOldAssetPath(), $this->assetTypeCreate);
    }


}