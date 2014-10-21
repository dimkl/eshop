<?php

/**
 * Description of RouterException
 *
 * @author dimkl
 */
class RouterException extends Exception {
    
}

/**
 * 
 */
abstract class ControllerType {

    const AJAX = 1;
    const NAVIGATION = 2;

    public static function getType($name) {
        if (!is_string($name)) {
            throw new Exception("Name in Controller type is not a string");
        }
        if (strtolower($name) === "ajax") {
            return ControllerType::AJAX;
        } else if (strtolower($name) === "navigation") {
            return ControllerType::NAVIGATION;
        }
        throw new Exception("Name in Controller type does not correspond to a valid type");
    }

    public static function getName($type) {
        if (!is_int($type)) {
            throw new Exception("Type in Controller type is not an int");
        }

        if ($type === ControllerType::AJAX) {
            return "ajax";
        } else if ($type === ControllerType::NAVIGATION) {
            return "navigation";
        }
        throw new Exception("Type in Controller type is not valid type");
    }

}

/**
 * Description of Router
 *
 * @author dimkl
 */
class Router {

    private static $pathInfo = "";
    private static $controller = "";
    private static $controllerAction = "";
    private static $controllerParameters = [];
//
    public static $controllerPath = "./controllers";
    public static $viewPath = "./views";
    public static $modelPath = "./models";
    public static $libPath = "./libs";
//
    public static $defaultController = "ProductController";
    public static $defaultAction = "index";
//
    public static $controllerSuffix = "Controller";
    public static $controllerPrefix = "";
    public static $controllerAjaxSuffix = "Ajax";
    public static $controllerAjaxPrefix = "";
//
    public static $controllerType = "";

    public static function initiate() {
        try {
            static::validatePathInfo();
//
            $_pathinfoParts = explode('/', static::$pathInfo);
            $_counter = count($_pathinfoParts);
//
            static::$controllerType = ControllerType::getType($_pathinfoParts[0]);
            static::$controller = $_pathinfoParts[1];
            static::$controllerAction = $_pathinfoParts[2];
            for ($i = 3; $i < $_counter; $i++) {
                static::$controllerParameters[$i - 3] = $_pathinfoParts[$i];
            }
        } catch (Exception $ex) {
//            throw new RouterException($ex->getMessage());
        }
    }

    public static function getController() {
        if (static::$controller === "") {
            static::initiate();
        }
        if (static::$controllerType === ControllerType::NAVIGATION) {
            return static::$controllerPrefix . static::$controller . static::$controllerSuffix;
        } else if (static::$controllerType == ControllerType::AJAX) {
            return static::$controllerAjaxPrefix . static::$controller . static::$controllerAjaxSuffix;
        }
        throw new RouterException("Controller type is not valid");
    }

    public static function getControllerPath() {
        try {
            $controllerName = static::getController();
            return static::$controllerPath . "/" . ControllerType::getName(static::$controllerType) . "/" . $controllerName;
        } catch (Exception $ex) {
            throw new RouterException("Controller type is not valid. " . $ex->getMessage());
        }
    }

    public static function getAction() {
        if (static::$controllerAction === "") {
            static::initiate();
        }
        return static::$controllerAction;
    }

    public static function getParameters() {
        if (static::$controllerParameters === "") {
            static::initiate();
        }
        return static::$controllerParameters;
    }

    /**
     * ValidatePathInfo static method validates and returns pathInfo data
     */
    private static function validatePathInfo() {
        if (!isset($_SERVER["PATH_INFO"])) {
            throw new RouterException('Path Info was not Found');
        }

        $_pathInfoParts = explode('/', $_SERVER["PATH_INFO"]);
        $_counter = count($_pathInfoParts);

        if ($_counter === 0) {
            static::$pathInfo = static::$defaultController . "/" . static::$defaultAction . "/";
        } else {
//remove first empty pathInfo
            array_shift($_pathInfoParts);
            $_counter--;
            if ($_counter === 1) {
                static::$pathInfo = $_pathInfoParts[0] . "/" . static::$defaultAction . "/";
            } else if ($_counter === 2) {
                static::$pathInfo = $_pathInfoParts[0] . "/" . $_pathInfoParts[1] . "/";
            } else {
                static::$pathInfo = implode('/', $_pathInfoParts);
            }
        }
    }

}
