<?php

namespace AndrewAndante\Locket\Controllers;

use SilverStripe\Control\Controller;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

class ProtectedPageController extends Controller
{
    protected function init()
    {
        parent::init();

        if (!Security::getCurrentUser()) {
            Security::permissionFailure(
                null,
                "You must be logged in to view this site. If you require access, please contact Andrew or Leah. If you don't know how, then you probably shouldn't have access."
            );
        }
    }

    public function getIsMobile()
    {
        $detector = new \Mobile_Detect();
        return $detector->isMobile();
    }

    public function getCurrentMemberIsAdmin()
    {
        $member = Security::getCurrentUser();
        return Permission::checkMember($member, 'ADMIN');
    }
}