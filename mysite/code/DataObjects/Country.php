<?php

/**
 * Class Country
 */
class Country extends DataObject {

    /**
     * @var array
     */
    private static $db = array(
        'Title' => 'Varchar(256)',
        'NationalityLabel' => 'Varchar(256)'
    );

    /**
     * @var array
     */
    private static $has_many = array(
        'Events' => 'EventPage',
        'Members' => 'Member'
    );

    private static $summary_fields = array(
        'Title' => 'Name',
        'NationalityLabel' => 'Nationality'
    );

    /**
     * @var array
     */
    private static $api_information = array(
        'ID' => 'ID',
        'Title' => 'Title'
    );


}
