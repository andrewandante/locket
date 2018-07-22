<?php

namespace AndrewAndante\Locket\Controllers;

use DateTime;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Folder;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\View\Requirements;

class GalleryController extends ProtectedPageController
{
    protected function init()
    {
        parent::init();

        Requirements::javascript('themes/photography-master/js/modernizr.min.js');
        Requirements::javascript('themes/photography-master/js/main.js');
        Requirements::javascript('themes/photography-master/js/jquery.js');
        Requirements::javascript('themes/photography-master/js/jquery-ui-1.10.4.min.js');
        Requirements::javascript('themes/photography-master/dist/js/jasny-bootstrap.min.js');
        Requirements::javascript('themes/photography-master/js/bootstrap.min.js');
        Requirements::javascript('themes/photography-master/js/isotope.js');
        Requirements::javascript('themes/photography-master/js/lightbox.js');
        Requirements::javascript('themes/photography-master/js/animated-masonry-gallery.js');

        Requirements::javascript('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js');

        Requirements::set_force_js_to_bottom(true);

        Requirements::css('themes/photography-master/css/style.css');
        Requirements::css('themes/photography-master/dist/css/jasny-bootstrap.min.css');
        Requirements::css('themes/photography-master/css/bootstrap.min.css');
        Requirements::css('themes/photography-master/css/full-slider.css');
        Requirements::css('themes/photography-master/css/Icomoon/style.css');
        Requirements::css('themes/photography-master/css/animated-masonry-gallery.css');
        Requirements::css('themes/photography-master/css/navmenu-reveal.css');
        Requirements::css('themes/photography-master/css/lightbox.css');

        Requirements::css('//fonts.googleapis.com/css?family=Raleway');
        Requirements::css('//fonts.googleapis.com/css?family=Berkshire+Swash');
        Requirements::css('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    }

    public function getCurrentAlbumID()
    {
        return $this->request->getVar('album');
    }

    public function getCurrentAlbumTitle()
    {
        $folder = Folder::get()->byID($this->getCurrentAlbumID());
        if ($folder && $folder->exists()) {
            return $folder->getCleanTitle();
        }
        return 'All gallery items';
    }

    public function getAllAlbums()
    {
        $folders = Folder::get()->exclude(['Title' => 'Uploads']);
        return $folders->filterByCallback(function ($folder) {
            /** @var Folder $folder */
            return $folder->hasChildren();
        });
    }

    public function getAllImages()
    {
        $images = Image::get();
        $album = $this->getCurrentAlbumID();
        if ($album && strtolower($album) !== 'all') {
            $images = $images->filter(['ParentID' => $album]);
        }
        $images = $images->sort('OriginalDate', 'DESC');
        return PaginatedList::create($images, $this->request);
    }

    public function getYears()
    {
        $years = ArrayList::create();
        foreach ($this->getAllImages() as $image) {
            $candidateYear = ((new DateTime($image->getDisplayDate()))->format('Y'));
            if (!($years->find('Year', $candidateYear))) {
                $years->push(ArrayData::create([
                    'Year' => $candidateYear
                ]));
            }
        }
        return $years;
    }

}
