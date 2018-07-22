<?php

namespace AndrewAndante\Locket\Model;

use DateTime;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBDatetime;
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

    public function onBeforeWrite()
    {
        /** @var Image $image */
        $image = $this->getOwner();
        if (!$image->OriginalDate) {
            $filePath = implode('/', [PUBLIC_PATH, ASSETS_DIR, '.protected', $image->File->getMetaData()['path']]);
            $exifData = exif_read_data($filePath);
            if ($exifData && isset($exifData['DateTimeOriginal'])) {
                $dateTimeOriginal = DateTime::createFromFormat('Y:m:d H:i:s', $exifData['DateTimeOriginal']);
                $image->OriginalDate = DBDatetime::create()->setValue($dateTimeOriginal->format(DATE_ISO8601));
            }
        }
    }
}