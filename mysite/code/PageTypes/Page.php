<?php

/**
 * Class Page
 */
class Page extends SiteTree
{

}

/**
 * Class Page_Controller
 */
class Page_Controller extends ContentController
{
    public function WebpackDevServer()
    {
        if(Director::isDev()) {
            $socket = @fsockopen('localhost', 3000, $errno, $errstr, 1);
            return !$socket ? false : true;
        }
    }
}
