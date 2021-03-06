<?php

namespace AndrewAndante\Locket\Controllers;

use SilverStripe\Assets\Image;
use SilverStripe\View\Requirements;

class HomeController extends ProtectedPageController
{
    private static $allowed_actions = [
        'index'
    ];

    protected function index()
    {
        return $this->renderWith("AndrewAndante\Locket\Controllers\HomeController");
    }

    protected function init()
    {
        parent::init();

        Requirements::javascript('themes/photography-master/js/main.js');
        Requirements::javascript('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js');
        Requirements::javascript('themes/photography-master/dist/js/jasny-bootstrap.min.js');
        Requirements::javascript('themes/photography-master/js/bootstrap.min.js');
        Requirements::customScript('<<<JS
            $(\'.carousel\').carousel({
            interval: 6000 //changes the speed
            })
        JS');

        Requirements::css('themes/photography-master/css/style.css');
        Requirements::css('themes/photography-master/dist/css/jasny-bootstrap.min.css');
        Requirements::css('//fonts.googleapis.com/css?family=Raleway');
        Requirements::css('//fonts.googleapis.com/css?family=Berkshire+Swash');
        Requirements::css('themes/photography-master/css/bootstrap.min.css');
        Requirements::css('themes/photography-master/css/navmenu-reveal.css');
        Requirements::css('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

    }

    public function getCarouselImages()
    {
        return Image::get()->sort('OriginalDate', 'DESC')->limit('3');
    }
}