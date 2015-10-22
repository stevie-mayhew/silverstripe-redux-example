<?php

class EventService extends DataObjectService
{

    private static $class_name = 'EventPage';

    private static $items_per_page = 5;

    private static $filter_attributes = array(
        'CountryID',
        'EventTypeID',
        'ID',
        'Title'
    );

    protected $request;

    public function __construct($request)
    {
        $this->setItemsPerPage($this->stat('items_per_page'));
        parent::__construct($request);
    }

    public function filterObjects(SS_List $events)
    {
        $events = parent::filterObjects($events);

        if ($this->request->getVar('country') && $this->request->getVar('country') > 0) {
            $events = $events->filter('CountryID', intval($this->request->getVar('country')));
        }

        if ($this->request->getVar('eventType') && $this->request->getVar('eventType') > 0) {
            $events = $events->filter('EventTypeID', intval($this->request->getVar('eventType')));
        }

        if ($this->request->getVar('month') && $this->request->getVar('month') > 0) {

            $sanitizeMonth = date("Y-m-d H:i:s", $this->request->getVar('month'));
            $month = date('Y-m-d H:i:s', strtotime($sanitizeMonth));
            $plusOneMonth = date('Y-m-d H:i:s', strtotime($sanitizeMonth . " + 1 month"));
            $events = $events->filter(
                array(
                    'StartTime:GreaterThan' => $month,
                    'StartTime:LessThan' => $plusOneMonth
                )
            );
        }
        
        if ($this->request->getVar('keywords') && $this->request->getVar('keywords') !== '') {

            $keywords = $this->request->getVar('keywords');
            $events = $events->filterAny(
                array(
                    'Title:PartialMatch' => $keywords,
                    'Content:PartialMatch' => $keywords
                )
            );

        }

        return $events;
    }

}
