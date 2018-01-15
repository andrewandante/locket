<?php

namespace AndrewAndante\Locket\Controllers;

use AndrewAndante\Locket\Model\Invite;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\ConfirmedPasswordField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

class InviteController extends Controller
{

    private static $allowed_actions = array(
        'index',
        'accept',
        'success',
        'InvitationForm',
        'AcceptForm',
        'expired',
        'notfound'
    );

    private static $url_handlers = [
        'accept/$ID/AcceptForm' => 'AcceptForm',
    ];

    public function index()
    {
        if (!Security::getCurrentUser()) {
            return $this->redirect('/Security/login');
        } elseif (!Permission::check('ADMIN')) {
            return Security::permissionFailure();
        } else {
            return $this->renderWith(self::class);
        }
    }

    public function InvitationForm()
    {
        $fields = FieldList::create(
            TextField::create('FirstName', 'First name:'),
            TextField::create('Surname', 'Last name:'),
            EmailField::create('Email', 'Email address:')
        );
        $actions = FieldList::create(
            FormAction::create('sendInvite', 'Send Invitation')
        );
        $requiredFields = RequiredFields::create('FirstName', 'Surname', 'Email');

        return Form::create($this, 'InvitationForm', $fields, $actions, $requiredFields);
    }

    /**
     * Records and sends the user's invitation
     * @param $data
     * @param Form $form
     * @return bool|HTTPResponse
     */
    public function sendInvite($data, Form $form)
    {
        if (!Permission::check('ADMIN')) {
            $form->sessionMessage('You do not have permission to create user invitations', 'bad');
            return $this->redirectBack();
        }
        if (!$form->getValidator()->validate()) {
            $form->sessionMessage(
                sprintf(
                    'At least one error occurred while trying to save your invite: %s',
                    $form->getValidator()->getErrors()[0]['fieldName']
                ),
                'bad'
            );
            return $this->redirectBack();
        }

        $invite = Invite::create();
        $form->saveInto($invite);
        try {
            $invite->write();
        } catch (ValidationException $e) {
            $form->sessionMessage($e->getMessage(), 'bad');
            return $this->redirectBack();
        }
        $invite->sendInvitation();

        $form->sessionMessage(sprintf('An invitation was sent to %s.', $data['Email']), 'good');
        return $this->redirectBack();
    }


    public function accept()
    {
        $hash = $this->getRequest()->param('ID');

        if (!$hash) {
            return $this->forbiddenError();
        }

        if ($invite = Invite::get()->filter('TempHash', $hash)->first()) {
            if ($invite->isExpired()) {
                return $this->redirect($this->Link('expired'));
            }
        } else {
            return $this->redirect($this->Link('notfound'));
        }
        return $this->renderWith(self::class.'_accept', ['Invite' => $invite]);
    }

    public function AcceptForm()
    {
        $hash = $this->getRequest()->param('ID');
        $invite = Invite::get()->filter('TempHash', $hash)->first();
        $firstName = $invite ? $invite->FirstName : '';
        $surname = $invite ? $invite->Surname : '';

        $fields = FieldList::create(
            TextField::create('FirstName', 'First name:', $firstName),
            TextField::create('Surname', 'Last name:', $surname),
            ConfirmedPasswordField::create('Password'),
            HiddenField::create('HashID', 'HashID', $hash)
        );
        $actions = FieldList::create(
            FormAction::create('saveInvite', 'Sign up!')
        );
        $requiredFields = RequiredFields::create('FirstName', 'Surname');
        $form = Form::create($this, 'AcceptForm', $fields, $actions, $requiredFields);
        $form->setFormAction(Controller::join_links($this->Link('accept'), $hash, $form->getName()));
        $this->getRequest()->getSession()->set('UserInvitation.accepted', true);
        return $form;
    }

    /**
     * @param $data
     * @param Form $form
     * @return bool|HTTPResponse
     */
    public function saveInvite($data, Form $form)
    {
        $invite = Invite::get()->filter('TempHash', $data['HashID'])->first();
        if (!$invite) {
            return $this->notFoundError();
        }
        if ($form->getValidator()->validate()) {
            $member = Member::create(['Email' => $invite->Email]);
            $form->saveInto($member);
            try {
                if ($member->validate()) {
                    $member->write();
                }
            } catch (ValidationException $e) {
                $form->sessionMessage($e->getMessage(), 'bad');
                die('invalid');
                return $this->redirectBack();
            }
            // Delete invitation
            $invite->delete();
            return $this->redirect($this->Link('success'));
        } else {
            $form->sessionMessage(Convert::array2json($form->getValidator()->getErrors()), 'bad');
            die('form invalid');
            return $this->redirectBack();
        }
    }

    public function success()
    {
        return $this->renderWith(self::class.'_success', ['BaseURL' => Director::absoluteBaseURL()]);
    }

    public function expired()
    {
        return $this->renderWith(self::class.'_expired');
    }

    public function notfound()
    {
        return $this->renderWith(self::class.'_notfound');
    }

    private function forbiddenError()
    {
        return $this->httpError(403, 'You must be logged in to access this page.');
    }

    private function notFoundError()
    {
        return $this->redirect($this->Link('notfound'));
    }

    /**
     * Ensure that links for this controller use the customised route.
     * Searches through the rules set up for the class and returns the first route.
     *
     * @param  string $action
     * @return string
     */
    public function Link($action = null)
    {
        if ($url = array_search(get_called_class(), (array) Config::inst()->get(Director::class, 'rules'))) {
            // Check for slashes and drop them
            if ($indexOf = stripos($url, '/')) {
                $url = substr($url, 0, $indexOf);
            }
            return $this->join_links($url, $action);
        }
    }
}