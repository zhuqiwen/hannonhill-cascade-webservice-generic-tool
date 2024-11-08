<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\MetadataNotExistException;
use phpDocumentor\Reflection\Types\This;

trait MetadataTraits{



    /**
     * oldAsset getters
     */
    public function getOldMetadataByName(string $metadataName):string
    {
        $oldAsset = $this->getOldAsset();

        if (isset($oldAsset->metadata)){
            $metadataArray = get_object_vars($oldAsset->metadata);
            unset($metadataArray['dynamicFields']);
            if (in_array($metadataName, array_keys($metadataArray))){
                $result = $metadataArray[$metadataName];
            }else{
                throw new \RuntimeException($metadataName . ' is not a valid attribute name. Valid attributes are: ' . implode(', ', array_keys($metadataArray)));
            }
        }else{
            throw new MetadataNotExistException('This asset: ['. $this->assetTypeDisplay . ']' . $oldAsset->path . ' does not have metadata ');
        }

        return $result;


    }

    public function getOldMetadataAuthor():string
    {
        return $this->getOldAsset()->metadata->author;
    }

    public function getOldMetadataDisplayName():string
    {
        return $this->getOldAsset()->metadata->displayName;
    }

    public function getOldMetadataEndDate():string
    {
        return $this->getOldAsset()->metadata->endDate;
    }

    public function getOldMetadataKeywords():string
    {
        return $this->getOldAsset()->metadata->keywords;
    }

    public function getOldMetadataDescription():string
    {
        return $this->getOldAsset()->metadata->metaDescription;
    }

    public function getOldMetadataReviewDate():string
    {
        return $this->getOldAsset()->metadata->reviewDate;
    }

    public function getOldMetadataStartDate():string
    {
        return $this->getOldAsset()->metadata->startDate;
    }

    public function getOldMetadataSummary():string
    {
        return $this->getOldAsset()->metadata->summary;
    }

    public function getOldMetadataTeaser():string
    {
        return $this->getOldAsset()->metadata->teaser;
    }

    public function getOldMetadataTitle():string
    {
        return $this->getOldAsset()->metadata->title;
    }

    public function getOldMetadataDynamicFieldByName(string $dynamicFieldName):string | array
    {
        //convert dynamicFields to array
        $dynamicFields = $this->getOldAsset()->metadata->dynamicFields;
        if (is_null($dynamicFields)){
            $dynamicFields = new \stdClass();
        }
        $convertedDynamicFieldsArray = $this->convertDynamicFields2Array($dynamicFields);
        //query the data

        //return it
        return $convertedDynamicFieldsArray[$dynamicFieldName] ?? throw new \OutOfBoundsException($dynamicFieldName . ' does not exist in metadata dynamic fields');
    }
    public function getNewMetadataDynamicFieldByName(string $dynamicFieldName):string | array
    {
        //convert dynamicFields to array
        $dynamicFields = $this->getNewAsset()->metadata->dynamicFields;
        if (is_null($dynamicFields)){
            $dynamicFields = new \stdClass();
        }
        $convertedDynamicFieldsArray = $this->convertDynamicFields2Array($dynamicFields);
        //query the data

        //return it
        return $convertedDynamicFieldsArray[$dynamicFieldName] ?? throw new \OutOfBoundsException($dynamicFieldName . ' does not exist in metadata dynamic fields');
    }

    public function setNewMetadataDynamicField(string $dynamicFieldName, string | array $valueStringOrValuesArray):void
    {
        //convert new metadata to array
        $dynamicFields = $this->getNewAsset()->metadata->dynamicFields;
        //set up the converted array
        $convertedMetadataArray = $this->convertDynamicFields2Array($dynamicFields);
        $convertedMetadataArray[$dynamicFieldName] = $valueStringOrValuesArray;
        //convert the array back to new metadata
        $newDynamicFields = $this->convertArray2DynamicFields($convertedMetadataArray);
        $this->getNewAsset()->metadata->dynamicFields = $newDynamicFields;

    }

    /**
     *
     * @param \stdClass $dynamicFields
     * @return array
     */
    public function convertDynamicFields2Array(\stdClass $dynamicFields):array
    {
        $dynamicFields = $dynamicFields->dynamicField ?? [];
        $result = [];
        
        if ($dynamicFields instanceof \stdClass){
            $dynamicFields = [$dynamicFields];
        }
        foreach ($dynamicFields as $dynamicField) {
            $fieldValues = $dynamicField->fieldValues->fieldValue ?? [];

            if ($fieldValues instanceof \stdClass){
                $fieldValues = [$fieldValues];
            }
            $tmpArray = [];
            foreach ($fieldValues as $fieldValue) {
                $tmpArray[] = $fieldValue->value;
            }

            $result[$dynamicField->name] = sizeof($tmpArray) <= 1 ? (empty($tmpArray) ? '' : $tmpArray[0]) : $tmpArray;
            
        }

        return $result;
    }

    /**
     * the input array looks like: [field-name1' => field-value, field-name2 => [field-value1, field-value2]]
     * @param array $dynamicFieldsArray
     * @return \stdClass|array
     */
    public function convertArray2DynamicFields(array $dynamicFieldsArray):\stdClass | array
    {
        $valueObjects = [];
        foreach ($dynamicFieldsArray as $name => $value) {
            // when value is a string
            $valueObject = (object)[
                'name' => $name,
                'fieldValues' => (object)[
                    'fieldValue' => (object)['value' => $value]
                ],
            ];
            //when value is an array
            if (is_array($value)){
                $tmpArray = [];
                foreach ($value as $item) {
                    $tmpArray[] = (object)['value' => $item];
                }
                $valueObject->fieldValues->fieldValue = $tmpArray;
            }

            $valueObjects[] = $valueObject;
        }

        return sizeof($valueObjects) == 1 ? $valueObjects[0] : $valueObjects;
    }
}