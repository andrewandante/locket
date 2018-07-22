<?php

namespace AndrewAndante\Locket\Task;

use DateTime;
use SilverStripe\Assets\Image;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\FieldType\DBDatetime;

class AddOriginalDateTask extends BuildTask
{

    /**
     * Implement this method in the task subclass to
     * execute via the TaskRunner
     *
     * @param HTTPRequest $request
     * @return
     */
    public function run($request)
    {
        $dryRun = $request->getVar('dryrun') ? true : false;
        $count = 0;
        $errorCount = 0;
        foreach (Image::get() as $image) {
            if (!$image->OriginalDate) {
                $filePath = implode('/', [ASSETS_PATH, '.protected', $image->File->getMetaData()['path']]);
                $exifData = exif_read_data($filePath);
                if ($exifData && isset($exifData['DateTimeOriginal'])) {
                    $dateTimeOriginal = DateTime::createFromFormat('Y:m:d H:i:s', $exifData['DateTimeOriginal']);
                    $originalDate = DBDatetime::create()->setValue($dateTimeOriginal->format('Y-m-d H:i:s'));
                    $image->setField('OriginalDate', $originalDate->format('y-MM-dd'));
                    if (!$dryRun) $image->write();
                    ++$count;
                } else {
                    ++$errorCount;
                }
            }
        }

        echo Image::get()->count() . " total images<br>";
        echo "$count images updated<br>";
        echo "$errorCount images failed to update<br>";
    }
}
