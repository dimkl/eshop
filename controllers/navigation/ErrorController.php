<?php

/**
 * Description of ErrorController
 *
 * @author dimkl
 */
class ErrorController extends Controller {

    protected function index() {
        echo "error page default";
//        View::load("error/default.html");
    }

    protected function notFound() {
        echo "error notFound";
//        View::load("error/default.html");
    }

}
