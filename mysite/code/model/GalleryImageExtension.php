<?php

namespace AndrewAndante\Locket\Model;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\InheritedPermissions;

class GalleryImageExtension extends DataExtension
{
    private static $db = [
        'OriginalDate' => 'DBDatetime',
    ];

//    private static $many_many = [
//        'GalleryImageTags' => GalleryImageTag::class,
//    ];

    private static $defaults = [
        'CanViewType' => InheritedPermissions::LOGGED_IN_USERS,
        'CanEditType' => InheritedPermissions::LOGGED_IN_USERS,
    ];

    public function getDisplayDate()
    {
        return $this->getOwner()->OriginalDate ? $this->getOwner()->dbObject('OriginalDate') : $this->getOwner()->dbObject('Created');
    }
}