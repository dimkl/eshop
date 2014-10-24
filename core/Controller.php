<?php

/**
 * Requirements for all Controller
 */
include './core/View.php';
include './core/Authorization.php';
/**
 * Requirements for only Ajax Controllers 
 */
include './core/Request.php';
include './core/Response.php';

/**
 * ControllerException class is an extention to Exception class for 
 *  Controller class exceptions
 */
class ControllerException extends Exception {
    
}

/**
 * interface for guidance of Controller class
 */
interface IController {

    public function execute();
}

/**
 * interface for guidance of Controller class for factory pattern methods
 */
interface IControllerFactory {

    static function factory($controllerPath, $controllerClass, $controllerAction, $controllerActionParameters);
}

/**
 * Description of Controller
 *
 * @author dimkl
 */
class Controller implements IController, IControllerFactory {

    const CONTROLLERBASEPATH = './controllers/';

    protected $action = 'index';
    protected $actionParams = [];

    private function __construct($action, $actionParams = []) {
        if (!method_exists($this, $action)) {
            throw new ControllerException('Method does not exist');
        }
        if (!is_array($actionParams)) {
            throw new ControllerException('Action parameters must be of type array');
        }
        $this->action = $action;
        $this->actionParams = $actionParams;
    }

    public function execute() {
        try {
            $returned = call_user_func_array([$this, $this->action], $this->actionParams);
            return $returned;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            $this->handleExceptions($ex);
        }
    }

    private static function load($controllerPath) {
        $controllerPath = Controller::CONTROLLERBASEPATH . $controllerPath;
        if (!file_exists($controllerPath)) {
            throw new Exception('Controller with name ' . get_called_class() . ' was not found');
        }
        include $controllerPath;
    }

    public static function factory($controllerPath, $controllerClass, $controllerAction, $controllerActionParameters) {
        try {
            //include class
            Controller::load($controllerPath);
            //create a controller instance
            return new $controllerClass($controllerAction, $controllerActionParameters);
        } catch (Exception $ex) {
            throw new ControllerException($ex);
        }
    }

    protected function handleExceptions(Exception $ex) {
        header('Location: ' . APPLICATIONFOLDER . 'navigation/error/');
    }

}
