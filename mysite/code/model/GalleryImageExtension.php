<?php

namespace AndrewAndante\Locket\Model;

use SilverStripe\ORM\DataExtension;

class GalleryImageExtension extends DataExtension
{
    private static $db = [
        'OriginalDate' => 'DBDatetime',
    ];

//    private static $many_many = [
//        'GalleryImageTags' => GalleryImageTag::class,
//    ];

    public function getDisplayDate()
    {
        return $this->getOwner()->OriginalDate ? $this->getOwner()->dbObject('OriginalDate') : $this->getOwner()->dbObject('Created');
    }
}