<?php

/**
 * Class SiteConfigExtension
 */
class SiteConfigExtension extends DataExtension implements PermissionProvider
{
    /**
     * @var array
     */
    private static $db = array(
        'ContactEmail' => 'Varchar(255)',
        'HeadTagCode' => 'Text',
        'OpenBodyTagCode' => 'Text',
        'CloseBodyTagCode' => 'Text'
    );

    /**
     * @var array
     */
    private static $defaults = array(
        'ContactEmail' => 'webmaster@littlegiant.co.nz'
    );

    /**
     * @return array
     */
    public function providePermissions()
    {
        return array(
            'ADD_CODE' => array(
                'name' => "Add Head/Body Code",
                'category' => _t('Permissions.CONTENT_CATEGORY', 'Content permissions')
            )
        );
    }

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab("Root.Details", TextField::create("ContactEmail", "Email for contact requests"));

        if (Permission::check('ADD_CODE')) {
            $fields->addFieldToTab("Root.Code", $headField = new TextareaField("HeadTagCode", "&lt;head&gt; code"));
            $fields->addFieldToTab("Root.Code", $bodyOpenField = new TextareaField("OpenBodyTagCode", "&lt;body&gt; open code"));
            $fields->addFieldToTab("Root.Code", $bodyCloseField = new TextareaField("CloseBodyTagCode", "&lt;body&gt; close code"));
        }
    }

    /**
     * Validate that the parameters entered into this configuration are valid
     *
     * @param ValidationResult $validationResult
     * @return Boolean
     */
    public function validate(ValidationResult $validationResult)
    {
        if (!Email::validEmailAddress($this->owner->ContactEmail)) {
            $validationResult->error(
                "Email for contact requests must be a valid email address.",
                "error"
            );
            return false;
        }
        return true;
    }

}
