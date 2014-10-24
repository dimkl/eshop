<?php

/**
 * ErrorController class is the controller for the error pages
 *
 * @author dimkl
 */
class ErrorController extends Controller {

    /**
     * Index method is responsible for rendering the default error page.
     * This method is acceessible through url 'navigation/error' 
     * 
     * @throws ControllerException
     */
    protected function index() {
        try {
            Authorization::allowOnly([UserType::GUEST, UserType::USER]);
        } catch (Exception $ex) {
            throw new ControllerException($ex);
        }
        View::render("error/default.html");
    }

    /**
     * NotFound method is responsible for rendering the notFound(404) error page.
     * This method is acceessible through url 'navigation/error/notFound' 
     * 
     * @throws ControllerException
     */
    protected function notFound() {
        try {
            Authorization::allowOnly([UserType::GUEST, UserType::USER]);
        } catch (Exception $ex) {
            throw new ControllerException($ex);
        }

        View::render("error/404.html");
    }

    /**
     * Forbidden method is responsible for rendering the forbidden error page.
     * This method is acceessible through url 'navigation/error/forbidden' 
     * 
     * @throws ControllerException
     */
    protected function forbidden() {
        try {
            Authorization::allowOnly([UserType::GUEST, UserType::USER]);
        } catch (Exception $ex) {
            throw new ControllerException($ex);
        }

        View::render("error/forbidden.html");
    }

}
