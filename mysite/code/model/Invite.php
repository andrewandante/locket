<?php

namespace AndrewAndante\Locket\Model;

use SilverStripe\Control\Director;
use SilverStripe\Control\Email\Email;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\RandomGenerator;
use SilverStripe\Security\Security;

class Invite extends DataObject
{
    private static $db = [
        'FirstName' => 'Varchar(50)',
        'Surname' => 'Varchar(50)',
        'Email' => 'Varchar(100)',
        'TempHash' => 'Varchar',
    ];

    private static $has_one = [
        'InvitedBy' => Member::class,
    ];

    private static $summary_fields = [
        'FirstName' => 'First Name',
        'Surname' => 'Last Name',
        'Email' => 'Email address',
        'Created' => 'Time sent',
    ];

    private static $table_name = 'Locket_Invite';

    /**
     * Removes the hash field from the list.
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('TempHash');
        return $fields;
    }

    public function onBeforeWrite()
    {
        if (!$this->ID) {
            $generator = new RandomGenerator();
            $this->TempHash = $generator->randomToken('sha1');
            $this->InvitedByID = Security::getCurrentUser()->ID;
        }
        parent::onBeforeWrite();
    }

    public function sendInvitation()
    {
        $toEmail = $this->Email;
        $toName = $this->FirstName;
        $fromName = $this->InvitedBy()->FirstName;
        $tempHash = $this->TempHash;
        return Email::create()
            ->setFrom('no-reply@ourlocket.com')
            ->setTo($toEmail)
            ->setSubject(sprintf('Invitation from %s', $fromName))
            ->setHTMLTemplate('Email\\Invitation')
            ->setData([
                'To' => $toName,
                'From' => $fromName,
                'TempHash' => $tempHash,
                'SiteURL' => Director::absoluteBaseURL(),
            ])
            ->send();
    }

    /**
     * Checks if a user invite was already sent, or if a user is already a member
     * @return ValidationResult
     */
    public function validate()
    {
        $valid = parent::validate();
        if (self::get()->filter('Email', $this->Email)->first()) {
            // UserInvitation already sent
            $valid->addError('This user was already sent an invite.');
        }
        if (Member::get()->filter('Email', $this->Email)->first()) {
            // Member already exists
            $valid->addError('This person is already a member of this system.');
        }
        return $valid;
    }
    /**
     * Checks if this invitation has expired
     * @return bool
     */
    public function isExpired()
    {
        $days = Config::inst()->get(self::class, 'days_to_expiry');
        $time = DBDatetime::now()->getTimestamp();
        $ago = abs($time - strtotime($this->Created));
        $rounded = round($ago / 86400);

        if ($rounded > $days) {
            return true;
        }
        return false;
    }

    /**
     * @param Member|null $member
     * @return bool|int
     */
    public function canCreate($member = null, $context = [])
    {
        return Permission::check('ADMIN');
    }
}