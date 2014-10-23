<?php

/**
 * ControllerType abstract class used as Enumeration with more functionality 
 * for getting some information relevant with controller and controller type
 */
abstract class ControllerType {

    /**
     * Controller type constant for type of Navigation
     */
    const NAVIGATION = 1;

    /**
     * Controller type constant for type of Ajax
     */
    const AJAX = 2;

    /**
     * Controller type constant for type of Error
     */
    const ERROR = 3;

    /**
     * Controller settings for Navigation Controller Type 
     * 
     * @var array 
     */
    private static $navigationSettings = [
        "context" => "navigation",
        "prefix" => "",
        "suffix" => "Controller"
    ];

    /**
     * Controller settings for Ajax Controller Type
     * 
     * @var array 
     */
    private static $ajaxSettings = [
        "context" => "ajax",
        "prefix" => "",
        "suffix" => "Ajax"
    ];

    /**
     * Controller settings for Error Controller Type
     * 
     * @var array 
     */
    private static $errorSettings = [
        "context" => "navigation",
        "prefix" => "",
        "suffix" => "Controller"
    ];

    /**
     * Get Controller context (folder) based on string supplied
     * 
     * @param string $urlContext
     * @return string
     * @throws Exception if $urlContext isn't valid
     */
    public static function getContext($urlContext) {
        if (!is_string($urlContext)) {
            throw new Exception("Url Context supplied in ControllerType.getContext method must be of type string");
        }
        if ($urlContext === static::$navigationSettings["context"]) {
            return static::$navigationSettings["context"];
        } else if ($urlContext === static::$ajaxSettings["context"]) {
            return static::$ajaxSettings["context"];
        } else {
            return static::$errorSettings["context"];
        }
    }

    /**
     *  Get Controller type based on string supplied
     * 
     * @param string $urlContext
     * @return int
     * @throws Exception
     */
    public static function getType($urlContext) {
        if (!is_string($urlContext)) {
            throw new Exception("Url Context supplied in ControllerType.getType method must be of type string");
        }
        if ($urlContext === static::$navigationSettings["context"]) {
            return ControllerType::NAVIGATION;
        } else if ($urlContext === static::$ajaxSettings["context"]) {
            return ControllerType::AJAX;
        }
        return ControllerType::ERROR;
    }

    /**
     * Get Preffix for controller class based on the controller type
     * 
     * @param string $urlContext
     * @return string
     * @throws Exception if supplied context string is not of string type
     */
    public static function getPrefix($urlContext) {
        if (!is_string($urlContext)) {
            throw new Exception("Url Context supplied in ControllerType.getPrefix method must be of type string");
        }
        if ($urlContext === static::$navigationSettings["context"]) {
            return static::$navigationSettings["prefix"];
        } else if ($urlContext === static::$ajaxSettings["context"]) {
            return static::$ajaxSettings["prefix"];
        }
        return static::$errorSettings["prefix"];
    }

    /**
     * Get Suffix for controller class based on the controller type
     * 
     * @param string $urlContext
     * @return string
     * @throws Exception if supplied context string is not of string type
     */
    public static function getSuffix($urlContext) {
        if (!is_string($urlContext)) {
            throw new Exception("Url Context supplied in ControllerType.getSuffix method must be of type string");
        }
        if ($urlContext === static::$navigationSettings["context"]) {
            return static::$navigationSettings["suffix"];
        } else if ($urlContext === static::$ajaxSettings["context"]) {
            return static::$ajaxSettings["suffix"];
        }
        return static::$errorSettings["suffix"];
    }

}

/**
 *  Router class used for get routing request that derive from SERVER global variable 
 *  to the correspoding controller
 *
 * @author dimkl
 */
class Router {

    /**
     * $pathSegments is $_SERVER['PATH_INFO'] variable parts
     * 
     * @var array
     */
    private static $pathSegments = [];

    /**
     * $controllerContext is the folder name of the subfolder in 'controllers' folder,
     *  initiated with 'error' value as default 
     * 
     * @var string 
     */
    private static $controllerContext = "error";

    /**
     * Controller to be called class name , initiated with 'ErrorController' 
     *  as default value
     * 
     * @var string 
     */
    private static $controllerName = "ErrorController";

    /**
     * Controller to be called method name , initiated with 'index' as default value
     * 
     * @var string 
     */
    private static $controllerAction = "index";

    /**
     * Controller to be called method parameters , initiated as empty array 
     * 
     * @var array 
     */
    private static $controllerActionParameters = [];

    /**
     * init static method is used for router initiation based on PATH_INFO information
     * 
     * @return void
     * @throws Exception if path info is incorrect
     */
    public static function init() {
        //get path info initiate static properties
        if (!isset($_SERVER["PATH_INFO"])) {
            return;
        }
        try {
            static::initPathSegments();
            static::initControllerParts();
        } catch (Exception $ex) {
            throw new Exception("Path info missing information." . $ex->getMessage());
        }
    }

    /**
     * initPathSegments is called from 'init' to initiate static $pathSegmennts property
     *  with PATH_INFO exploded parts
     */
    private static function initPathSegments() {
        $pathInfo = trim(strtolower($_SERVER["PATH_INFO"]), '/');
        static::$pathSegments = explode("/", $pathInfo);
    }

    /**
     * initControllerParts  is called from 'init' to initiate static properties
     *  that are used for routing Controllers
     */
    private static function initControllerParts() {
        if (count(static::$pathSegments) < 1) {
            return;
        }
        $pathSegments = static::$pathSegments;
        $counter = count($pathSegments);
        //initiate controller information
        switch ($counter) {
            case 3:
                static::$controllerAction = array_splice($pathSegments, 2, 1)[0];
            case 2:
                static::$controllerName = array_splice($pathSegments, 1, 1)[0];
            case 1:
                static::$controllerContext = array_splice($pathSegments, 0, 1)[0];
                break;
            default:
                static::$controllerAction = array_splice($pathSegments, 2, 1)[0];
                static::$controllerName = array_splice($pathSegments, 1, 1)[0];
                static::$controllerContext = array_splice($pathSegments, 0, 1)[0];

                $counter-=3;
                static::$controllerActionParameters = array_slice($pathSegments, 0, $counter);
        }
    }

    /**
     * getControllerPath static method used to return the full path of the controller 
     *  file that is requested by the user
     * 
     * @return string
     */
    public static function getControllerPath() {
        return static::$controllerContext . "/" . static::getControllerName() . ".php";
    }

    /**
     * Getter static method used for getting cotroller folder-context
     * 
     * @return string
     */
    public static function getControllerContext() {
        return static::$controllerContext;
    }

    /**
     * Getter static method used for getting cotroller class name 
     * 
     * @return string
     */
    public static function getControllerName() {
        $controllerName = ControllerType::getPrefix(static::$controllerContext)
                . static::$controllerName
                . ControllerType::getSuffix(static::$controllerContext);
        return ucfirst($controllerName);
    }

    /**
     * Getter static method for getting controller method name that will be called
     * 
     * @return string
     */
    public static function getControllerAction() {
        return static::$controllerAction;
    }

    /**
     * Getter static method for getting the controller method parameters of the 
     *  method to be called
     * 
     * @return array
     */
    public static function getControllerActionParameters() {
        return static::$controllerActionParameters;
    }

    /**
     * Getter static method for getting a codename used in views and Authorization
     * 
     * @return string 
     */
    public static function getPageCodeName() {
        return strtolower(static::$controllerContext . '-' . static::$controllerName . '-' . static::$controllerAction);
    }

}
