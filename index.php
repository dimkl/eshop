<?php

/**
 * Gloabl Requirements
 */
include './core/IController.php';
include './core/IAjax.php';

include './libs/Database.php';
include './libs/Router.php';
include './libs/Model.php';

/**
 * Server is a class responsible for the request on server
 */
class Server {

    private $database;
    private $controller;

    /**
     * 
     */
    public function __construct() {
        Router::initiate();
    }

    /**
     * 
     */
    public function run() {
        try {
            $this->database = new DatabaseProvider("mysql", "localhost", "3306", "root", "", "eshop");
            Model::initFactory($this->database);
            //load Controller
            $this->loadController();
            //executeController
            $this->executeController(Router::getController());
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    private function loadController() {
        //get controller class 
        $controllerFile = Router::getControllerPath() . '.php';

        if (!file_exists($controllerFile)) {
            include Router::$viewPath . "/404.html";
            exit();
        }
        include $controllerFile;
    }

    private function executeController($controllerName) {
        //execute fuction
        $this->controller = new $controllerName();
        $method = Router::getAction();
        $parameters = Router::getParameters();
        call_user_func([$this->controller, $method], $parameters);
    }

}

$server = new Server;
$server->run();

