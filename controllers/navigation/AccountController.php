<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccountController
 *
 * @author dimkl
 */
class AccountController implements IController {

    public function register() {
        include Router::$viewPath . '/user/register.php';
    }

    public function login() {
        include Router::$viewPath . '/user/login.php';
    }

}
