<?php

namespace AndrewAndante\Locket\Model;

use SilverStripe\Core\Extension;

class FolderExtension extends Extension
{
    public function getCleanTitle()
    {
        return str_replace('-', ' ', $this->getOwner()->Title);
    }
}