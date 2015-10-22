<?php

/**
 * Class AccessPoint
 */
class AccessPoint extends Object
{


    /**
     * @param $code
     * @param $message
     * @return SS_HTTPResponse
     */
    protected function response($code, $message)
    {
        $body = '';

        if(!is_array($message)){

            $responseTitle = 'message';

            if($code > 300){
                $responseTitle = 'ErrorMessage';
            }

            // only display error messages in test mode
            if($code == 500 && Director::isLive()){
                $message = 'There was a server error';
            }

            $body = json_encode(
                array(
                    $responseTitle => $message
                )
            );
        }

        if(is_array($message)){
            $body = json_encode($message);
        }

        $response = new SS_HTTPResponse($body, $code);
        return $response;
    }

} 