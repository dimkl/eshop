<?php

/**
 * Description of AccountController
 *
 * @author dimkl
 */
class AccountController extends Controller {

    protected function register() {
        View::render('account/register.php');
    }

    protected function login() {
        View::render('account/login.php');
    }

    protected function logout() {
        try {
            Authorization::unAuthorizeUser();
        } catch (Exception $ex) {
            throw $ex;
        }
        View::render('account/login.php');
    }

}
