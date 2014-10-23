<?php

/**
 * Description of Request
 *
 * @author dimkl
 */
class Request {

    const GETMETHOD = 1;
    const POSTMETHOD = 2;
    const PUTMETHOD = 3;
    const DELETEMETHOD = 4;

    public static function getQueryParameter($parameterName) {

        return null;
    }

    public static function getPostData($parameterName) {

        return null;
    }

    public static function sanitizeData($data) {

        return $data;
    }

    private static function getHttpMethod() {
        
    }

    public function allowHttpMethod($httpMethod) {
        if (!is_int($httpMethod)) {
            throw new Exception("Request::allowHttpMethod parameter httpMethod must be int type.");
        }
        if (static::getHttpMethod() !== $httpMethod) {
            Response::error("False http response");
        }
    }

}
