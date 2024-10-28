<?php

namespace Edu\IU\Framework\GenericUpdater\Utilities;

use Edu\IU\Framework\GenericUpdater\Asset\Asset;
use Edu\IU\Wcms\WebService\WCMSClient;

trait UtilitiesTraits{
    public WCMSClient $wcms;

    public function convertToArrayWhenOnly1Child(Asset $containerOrFolder):array
    {

        $childrenProperty = $containerOrFolder->getOldAsset()->children;

        return isset($childrenProperty->child) ?
            (is_array($childrenProperty->child) ?
                $childrenProperty->child
                :
                [$childrenProperty->child])
            :
            [];
    }

}