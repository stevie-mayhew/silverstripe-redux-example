<?php

/**
 * Class EventType
 */
class EventType extends DataObject {

    /**
     * @var array
     */
    private static $db = array(
        'Title' => 'Varchar(256)'
    );

    /**
     * @var array
     */
    private static $has_many = array(
        'Events' => 'EventPage'
    );

    /**
     * @var array
     */
    private static $api_information = array(
        'ID' => 'ID',
        'Title' => 'Title'
    );

}
