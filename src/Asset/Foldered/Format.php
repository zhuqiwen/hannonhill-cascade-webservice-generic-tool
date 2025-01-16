<?php

namespace Edu\IU\Framework\GenericUpdater\Asset\Foldered;

use Edu\IU\Framework\GenericUpdater\Exception\MetadataNotExistException;
use Edu\IU\Framework\GenericUpdater\Exception\TagsNotExistException;
use ReflectionMethod;

class Format extends Folder{
    //To disable metadata traits for format and velocity format
    // set all trait methods as private
    use MetadataTraits{
//        getOldMetadataByName as private;
//        getOldMetadataAuthor as private;
//        getOldMetadataDisplayName as private;
//        getOldMetadataEndDate as private;
//        getOldMetadataKeywords as private;
//        getOldMetadataDescription as private;
//        getOldMetadataReviewDate as private;
//        getOldMetadataStartDate as private;
//        getOldMetadataSummary as private;
//        getOldMetadataTeaser as private;
//        getOldMetadataTitle as private;
//        getOldMetadataDynamicFieldByName as private;
//        getNewMetadataDynamicFieldByName as private;
//        getOldMetadataDynamicFields as private;
//        getNewMetadataDynamicFields as private;
//        setNewMetadataDynamicField as private;
//        convertDynamicFields2Array as private;
//        convertArray2DynamicFields as private;
    }
    use TagsTraits{
//        getOldTags as private;
//        getNewTags as private;
//        setNewTags as private;
//        addNewTag as private;
    }
}