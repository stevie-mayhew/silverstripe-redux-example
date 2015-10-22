<?php

/**
 * Class EventAdmin
 */
class EventAdmin extends CatalogPageAdmin
{
    /**
     * @var array
     */
    private static $managed_models = array(
        'EventPage',
        'EventType'
    );

    /**
     * @var string
     */
    private static $url_segment = 'events';

    /**
     * @var string
     */
    private static $menu_title = 'Events';

}
