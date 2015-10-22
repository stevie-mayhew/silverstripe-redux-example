<?php

/**
 * Class WebService
 */
class WebServiceController extends Controller
{

    /**
     * @var array
     */
    private static $allowed_actions = array(
        'events',
        'countries',
        'months',
        'eventTypes',
    );

    /**
     * @param SS_HTTPRequest $request
     * @return $this
     * @throws SS_HTTPResponse_Exception
     */
    public function index(SS_HTTPRequest $request)
    {
        $action = $request->param('Action');
        if(in_array($action, $this->stat('allowed_actions'))){
            return $this->{$action}($request);
        }

        $this->httpError(404);
        return $this;
    }

    /**
     * @param $request
     * @return SS_HTTPResponse
     */
    public function events($request)
    {
        $response = Controller::curr()->getResponse();
        $service = new EventService($request);
        $objects = $service->processRequest();

        $responseData = array(
            'objects' => $service->formatObjects($service->paginateObjects($objects)),
            'count' => ceil($objects->count() / $service->getItemsPerPage())
        );

        $response = $response->setBody(json_encode($responseData));

        return $response;
    }

    /**
     * @return SS_HTTPRequest|SS_HTTPResponse
     */
    public function months()
    {
        $currentMonth = date('F Y');

        $months[] = array(
            'Title' => $currentMonth,
            'ID' => strtotime($currentMonth)
        );
        for ($i = 0; $i < 11; $i++) {
            $currentMonth = date('F Y', strtotime($currentMonth . " + 1 month"));
            $months[] = array(
                    'Title' => $currentMonth,
                    'ID' => strtotime($currentMonth)
                );
        }

        $responseData = array(
            'objects' => json_encode($months),
            'count' => 12
        );
        $response = Controller::curr()->getResponse();
        $response = $response->setBody(json_encode($responseData));
        return $response;
    }

    /**
     * @param $request
     * @return SS_HTTPRequest|SS_HTTPResponse
     */
    public function countries($request)
    {
        $response = Controller::curr()->getResponse();
        $service = new CountryService($request);
        $objects = $service->processRequest();
        $responseData = array(
            'objects' => $service->formatObjects($objects),
            'count' => $objects->count()
        );
        $response = $response->setBody(json_encode($responseData));
        return $response;
    }

    /**
     * @param $request
     * @return SS_HTTPRequest|SS_HTTPResponse
     */
    public function eventTypes($request)
    {
        $response = Controller::curr()->getResponse();
        $service = new EventTypeService($request);
        $objects = $service->processRequest();
        $responseData = array(
            'objects' => $service->formatObjects($objects),
            'count' => $objects->count()
        );
        $response = $response->setBody(json_encode($responseData));
        return $response;
    }

}
