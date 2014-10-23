<?php

include "./libs/Router.php";
include "./libs/Database.php";
include "./libs/Model.php";
include "./libs/Controller.php";

interface IServer {

    public function run();

    static function executeController();
}

/**
 * Description of Server
 *
 * @author dimkl
 */
class Server implements IServer {

    const DRIVER = "mysql";
    const HOSTNAME = "localhost";
    const PORT = 3306;
    const USERNAME = "eshop";
    const PASSWORD = "eshop";
    const DATABASE = "eshop";

    function run() {
        try {
            Router::init();

            Model::init(new DatabaseProvider(
                    Server::DRIVER, Server::HOSTNAME, Server::PORT
                    , Server::USERNAME, Server::PASSWORD
                    , Server::DATABASE)
            );

            $controller = static::executeController();
            $controller->execute();
        } catch (Exception $ex) {
            echo $ex->getMessage();
//            header("Location: /Eshop/navigation/error/");
        }
    }

    static function executeController() {
        try {
            $controllerName = Router::getControllerName();
            //include class
            Controller::load(Router::getControllerPath());
            //create a controller instance
            return new $controllerName(
                    Router::getControllerAction()
                    , Router::getControllerActionParameters()
            );
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
