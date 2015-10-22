<?php

/**
 * Class EventPage
 */
class EventPage extends Page
{
    /**
     * @var array
     */
    private static $db = array(
        'LocationName' => 'Varchar(256)',
        'LocationAddress' => 'Text',
        'StartTime' => 'SS_DateTime',
        'EndTime' => 'SS_DateTime'
    );
    /**
     * @var array
     */
    private static $has_one = array(
        'EventType' => 'EventType',
        'Image' => 'Image',
        'Country' => 'Country'
    );

    private static $api_information = array(
        'ID' => 'ID',
        'Title' => 'Title',
        'EventTypeID' => 'EventTypeID',
        'CountryID' => 'CountryID',
        'getShortContent' => 'Content',
        'StartTime' => 'StartTime',
        'getImageURL' => 'ImageURL',
        'LocationAddress' => 'LocationAddress',
        'AbsoluteLink' => 'Link'
    );

    public function getImageURL()
    {
        return $this->Image() && $this->Image()->exists() ? $this->Image()->CroppedImage(170,195)->URL : false;
    }

    public function getShortContent()
    {
        return $this->dbObject('Content')->LimitCharacters(200);
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $eventTypeSource = EventType::get()->map()->toArray();
        $countrySource = Country::get()->map()->toArray();

        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create('LocationName', 'Location Name'),
                TextareaField::create('LocationAddress', 'Location Address'),
                $startDate = DatetimeField::create('StartTime', 'Start'),
                $endDate = DatetimeField::create('EndTime', 'End'),
                TextareaField::create('Price', 'Price'),
                DropdownField::create('EventTypeID', 'Type', $eventTypeSource),
                DropdownField::create('CountryID', 'Country', $countrySource),
                UploadField::create('Image', 'Image')
            )
        );

        $date = Date('Y-m-d', time());
        $time = Date('H:i:s', time());

        $startDate->getDateField()->setConfig('showcalendar', true);
        $startDate->getDateField()->setValue($date);
        $startDate->getTimeField()->setValue($time);

        $endDate->getDateField()->setConfig('showcalendar', true);
        $endDate->getDateField()->setValue($date);
        $endDate->getTimeField()->setValue($time);


        return $fields;
    }

}

/**
 * Class EventPage_Controller
 */
class EventPage_Controller extends Page_Controller
{

}
