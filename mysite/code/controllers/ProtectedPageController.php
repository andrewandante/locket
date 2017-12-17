<?php

namespace AndrewAndante\Locket\Controllers;

use SilverStripe\Control\Controller;
use SilverStripe\Security\Security;

class ProtectedPageController extends Controller
{
    protected function init()
    {
        parent::init();

        if (!Security::getCurrentUser()) {
            Security::permissionFailure();
        }
    }
}