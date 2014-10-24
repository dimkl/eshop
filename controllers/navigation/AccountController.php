<?php

/**
 * AccountController class is the controller for account functionality pages
 *
 * @author dimkl
 */
class AccountController extends Controller {

    /**
     * Register method is responsible for rendering the register page.
     * This method is acceessible through url 'navigation/account/register' 
     * 
     * @throws ControllerException
     */
    protected function register() {
        try {
            Authorization::allowOnly([UserType::GUEST]);
        } catch (Exception $ex) {
            throw new ControllerException($ex);
        }

        View::render('account/register.php');
    }

    /**
     * Register method is responsible for rendering the login page.
     * This method is acceessible through url 'navigation/account/login' 
     * 
     * @throws ControllerException
     */
    protected function login() {
        try {
            Authorization::allowOnly([UserType::GUEST]);
        } catch (Exception $ex) {
            throw new ControllerException($ex);
        }

        View::render('account/login.php');
    }

    /**
     * Register method is responsible for loggin out the current user.
     * This method is acceessible through url 'navigation/account/logout' 
     * 
     * @throws ControllerException
     */
    protected function logout() {
        try {
            Authorization::allowOnly([UserType::USER]);
            Authorization::unAuthorizeUser();
        } catch (Exception $ex) {
            throw new ControllerException($ex);
        }

        header('Location: ' . APPLICATIONFOLDER . 'navigation/account/login');
    }

}
