<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use phpDocumentor\Reflection\Types\This;

trait TagsTraits{


    public function getOldTags():array
    {
        return $this->convertTags2Array($this->getOldAsset()->tags);
    }

    public function getNewTags():array
    {
        return $this->convertTags2Array($this->getNewAsset()->tags);
    }

    public function setNewTags(string | array $tagStringOrTagsArray):void
    {
        if (is_string($tagStringOrTagsArray)){
            $tagStringOrTagsArray = [$tagStringOrTagsArray];
        }

        $this->newAsset->tags = $this->convertArray2Tags($tagStringOrTagsArray);
    }

    public function addNewTag(string $tagString):void
    {
        $tagsArray = $this->getNewTags();
        if (!in_array($tagString, $tagsArray)){
            $tagsArray[] = $tagString;
        }

        $this->newAsset->tags = $this->convertArray2Tags($tagsArray);
    }

    private function convertTags2Array(\stdClass $tags):array
    {
        $result = [];
        if ($tags->tag) {
            $valuesArray = $tags->tag instanceof \stdClass ? [$tags->tag] : $tags->tag;
            foreach ($valuesArray as $item) {
                $result[] = $item->name;
            }
        }
        return $result;
    }

    private function convertArray2Tags(array $tagsArray):\stdClass
    {
        $result = new \stdClass();

        if (!empty($tagsArray)){
            $valueArray = [];
            foreach ($tagsArray as $item) {
                $tmpObj = new \stdClass();
                $tmpObj->name = $item;
                $valueArray[] = $tmpObj;
            }

            $result->tag = sizeof($valueArray) == 1 ? $valueArray[0] : $valueArray;
        }

        return $result;
    }


}