<?php

/**
 * Class DataObjectService
 */
abstract class DataObjectService extends AccessPoint {


    /**
     * @var string
     */
    private static $class_name;

    /**
     * @var
     */
    private $itemsPerPage;

    /**
     * An array of the return types to pull for the requested class
     *
     * array(
     *     'Title' => 'Title',
     *     'getLink' => 'URL'
     * );
     *
     * @var array
     */
    private $api_return = array();

    /**
     * @param SS_HTTPRequest $request
     * @throws Exception
     */
    public function __construct(SS_HTTPRequest $request)
    {
        $this->request = $request;


        $class = $this->stat('class_name');
        if (!$class) {
            throw new Exception('class_name must be implemented in subclasses of ' . __CLASS__);
        }

        $this->api_return = Config::inst()->get($class, 'api_information');

        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @param mixed $itemsPerPage
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return SS_HTTPResponse|SS_List
     */
    public function processRequest()
    {
        $class = Config::inst()->get(get_class($this), 'class_name');

        if($this->request->param('ID')){
            $id = intval($this->request->param('ID'));

            if($id > 0){
                $object = $class::get()->byID($id);
            }
        }

        return $this->getObjects();
    }

    /**
     * @return SS_HTTPResponse|SS_List
     */
    public function getObjects()
    {
        if(!class_exists($this->stat('class_name'))){
            return $this->response(500, 'Could not find class');
        }

        if(!(singleton($this->stat('class_name')) instanceof DataObject)){
            return $this->response(500, $this->stat('class_name') . ' is not an of the correct type.');
        }

        $class = $this->stat('class_name');

        $objects = $class::get();

        if(method_exists($this, 'filterObjects')){
            $objects = $this->filterObjects($objects);
        }

        return $objects;
    }

    /**
     * @param SS_List $objects
     * @return SS_List
     */
    public function filterObjects(SS_List $objects)
    {
        $filterable_attributes = Config::inst()->get(get_class($this), 'filter_attributes');

        if ($filterable_attributes) {
            foreach($filterable_attributes as $attribute)
            {
                if($value = $this->request->getVar($attribute)){
                    $objects = $objects->filter($attribute, $value);
                }
            }
        }

        return $objects;
    }

    /**
     * @param $objects
     * @return mixed
     */
    public function paginateObjects($objects)
    {
        $page = intval($this->request->getVar('page')) ?: 1;
        // let page reflect realistic integer, not array index
        $page = $page - 1;
        return $objects->limit($this->itemsPerPage, $page * $this->itemsPerPage);
    }

    /**
     * @param SS_List $objects
     * @return string
     */
    public function formatObjects(SS_List $objects)
    {
        $returnList = array();
        foreach ($objects as $object) {
            $returnObject = array();
            foreach ($this->api_return as $key => $title) {
                if ($object->hasMethod($key)) {
                    $returnObject[$title] = $object->{$key}();
                } else {
                    $returnObject[$title] = $object->{$key};
                }
            }
            $returnList[] = $returnObject;
        }

        return json_encode($returnList);
    }

}
