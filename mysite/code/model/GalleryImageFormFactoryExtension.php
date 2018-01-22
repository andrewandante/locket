<?php

namespace AndrewAndante\Locket\Model;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
//use SilverStripe\TagField\TagField;

class GalleryImageFormFactoryExtension extends Extension
{

    public function updateFormFields(FieldList $fields, $controller, $formName, $context)
    {
        $image = $context['Record'];
//        $tagField = TagField::create(
//            'GalleryImageTags',
//            'Tags',
//            GalleryImageTag::get(),
//            $image->GalleryImageTags()
//        );
        $fields->addFieldsToTab('Editor.Details', [
            DateField::create('OriginalDate', 'Date this was taken'),
//            $tagField
        ]);
    }
}