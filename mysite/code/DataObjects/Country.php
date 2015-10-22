<?php

/**
 * Class Country
 *
 * Combination previous `nationality` and `country_info` tables.
 */
class Country extends DataObject {

    /**
     * @var array
     */
    private static $db = array(
        'ISO' => 'Varchar(2)',
        'ISO3' => 'Varchar(3)',
        'Title' => 'Varchar(256)',
        'NationalityLabel' => 'Varchar(256)',
        'ImportID' => 'Int'
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
