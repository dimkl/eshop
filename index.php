<?php

include './core/ErrorHandler.php';
include './core/Server.php';

// set to the user defined error handler
$old_error_handler = set_error_handler('ErrorHandler::init');

session_start();

//global variables
define('APPLICATIONFOLDER', '/Eshop/');
//

$server = new Server();
$server->run();
