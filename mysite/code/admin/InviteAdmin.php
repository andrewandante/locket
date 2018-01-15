<?php

namespace AndrewAndante\Locket\Admin;

use AndrewAndante\Locket\Model\Invite;
use SilverStripe\Admin\ModelAdmin;

class InviteAdmin extends ModelAdmin
{
    private static $managed_models = [
        Invite::class
    ];

    private static $url_segment = 'invites';

    private static $menu_title = 'Invites';
}