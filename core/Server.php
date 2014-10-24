<?php

/**
 * Required libraries
 */
include './core/Router.php';
include './core/Database.php';
include './core/Model.php';
include './core/Controller.php';

/**
 * 
 */
interface IServer {

    public function run();
}

/**
 * Description of Server
 *
 * @author dimkl
 */
class Server implements IServer {

    const DRIVER = 'mysql';
    const HOSTNAME = 'localhost';
    const PORT = 3306;
    const USERNAME = 'eshop';
    const PASSWORD = 'eshop';
    const DATABASE = 'eshop';

    /**
     * run method inititiates all the processes that need to be done in order for 
     *  the response to be created and rendered or relpied.
     */
    function run() {
        try {
            Router::init();

            Model::init(new DatabaseProvider(
                    Server::DRIVER, Server::HOSTNAME, Server::PORT
                    , Server::USERNAME, Server::PASSWORD
                    , Server::DATABASE)
            );

            $controller = Controller::factory(Router::getControllerPath()
                            , Router::getControllerClass(), Router::getControllerAction()
                            , Router::getControllerActionParameters());
            $controller->execute();
        } catch (Exception $ex) {
            header('Location:' . APPLICATIONFOLDER . 'navigation/error/');
        }
    }

}
