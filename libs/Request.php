<?php

abstract class HttpMethods {

    const POST = 1;
    const GET = 2;
    const PUT = 3;
    const DELETE = 4;
    const HEAD = 5;
    const UNKNOWN = 6;

}

/**
 * 
 */
interface IRequest {

    static function getParameters();

    static function getHttpMethod();
}

/**
 * Description of Request
 *
 * @author dimkl
 */
class Request {

    private static $requestMethod;

    public static function getPostData() {
        $post = $_POST;
        if (empty($post)) {
            if (empty($GLOBALS["HTTP_RAW_POST_DATA"])) {
                throw new Exception("No post Data was found");
            }
            $post = json_decode($GLOBALS["HTTP_RAW_POST_DATA"], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Post data is not json data. please send json data");
            }
        }
        return Request::sanitize($post);
    }

    public static function sanitize($data) {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = Request::sanitize($v);
            } else {
                $data[$k] = htmlspecialchars($v, ENT_COMPAT, 'UTF-8');
            }
        }
        return $data;
    }

    static function getHttpMethod() {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            exit();
        }
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'PUT':
                static::$requestMethod = HttpMethods::PUT;
                break;
            case 'POST':
                static::$requestMethod = HttpMethods::POST;
                break;
            case 'GET':
                static::$requestMethod = HttpMethods::GET;
                break;
            case 'HEAD':
                static::$requestMethod = HttpMethods::HEAD;
                break;
            case 'DELETE':
                static::$requestMethod = HttpMethods::DELETE;
                break;
            default :
                static::$requestMethod = HttpMethods::UNKNOWN;
                break;
        }
        return static::$requestMethod;
    }

    public static function allowHttpMethod($httpMethod) {
        if (!is_int($httpMethod)) {
            throw new Exception("Request::allowHttpMethod parameter httpMethod must be int type.");
        }
        if (static::getHttpMethod() !== $httpMethod) {
            Response::error("False http response");
        }
    }

}
