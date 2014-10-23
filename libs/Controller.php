<?php

include './libs/Authorization.php';
include './libs/View.php';

/**
 * 
 */
interface IController {

    public function execute();

    static function load($controllerPath);
}

/**
 * Description of Controller
 *
 * @author dimkl
 */
class Controller implements IController {

    const CONTROLLERBASEPATH = "./controllers/";

    protected $action = "index";
    protected $actionParams = [];

    public function __construct($action, $actionParams = []) {
        if (!method_exists($this, $action)) {
            throw new Exception("Method does not exist");
        }
        if (!is_array($actionParams)) {
            throw new Exception("Action parameters must be of type array");
        }
        $this->action = $action;
        $this->actionParams = $actionParams;
    }

    public function execute() {
        if (!Authorization::check()) {
            
        }
        try {
            return call_user_func_array([$this, $this->action], $this->actionParams);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public static function load($controllerPath) {
        try {
            $controllerPath = Controller::CONTROLLERBASEPATH . $controllerPath;
            if (!file_exists($controllerPath)) {
                throw new Exception("Controller with name " . get_called_class() . " was not found");
            }
            include $controllerPath;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
