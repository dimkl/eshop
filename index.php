<?php

include './libs/ErrorHandler.php';
include './libs/Server.php';

// set to the user defined error handler
$old_error_handler = set_error_handler("ErrorHandler::init");

$server = new Server();
$server->run();
