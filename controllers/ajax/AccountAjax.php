<?php

/**
 * Description of AccountAjax
 *
 * @author dimkl
 */
class AccountAjax extends Controller {

    /**
     * Register method inserts a user to the database and returns a Response.
     * This ajax method is acceessible through url 'ajax/account/register' 
     * and request must be with post http method.
     */
    protected function register() {
        try {
            Request::allowHttpMethod(HttpMethods::POST);
            Authorization::ajaxAllowOnly([UserType::GUEST]);
        } catch (Exception $ex) {
            Response::error($ex->getMessage());
        }

        $data = Request::getPostData();
        //check password confirm
        if (!isset($data["password"], $data["confirm_password"])) {
            Response::error("confirm password or password is not set correctly");
        }
        //check and remove confirm_password
        if ($data["password"] !== $data["confirm_password"]) {
            Response::error("confirm password must match password");
        }
        unset($data["confirm_password"]);
        //
        try {
            //insert data to model
            Model::load(["UserModel"]);
            $user = new UserModel($data);
            //save model
            if ($user->register()) {
                //return response
                Response::ok(["message" => "Register was successfull!! In 3 sec you will be redirected to account/login page"]);
            }
            Response::error("Registration was not successfull." . implode('<br/>', $user->errorMessages));
        } catch (Exception $ex) {
            Response::error("Error occured during registration: " . $ex->getMessage());
        }
    }

    /**
     * Login method checks if a user with a username and password exists in the database
     * and returns a Ressponse. This ajax method is acceessible through url 'ajax/account/login' 
     * and request must be with post http method.
     */
    protected function login() {

        try {
            Request::allowHttpMethod(HttpMethods::POST);
            Authorization::ajaxAllowOnly([UserType::GUEST]);
        } catch (Exception $ex) {
            Response::error($ex->getMessage());
        }
        try {
            $data = Request::getPostData();
            //insert data to model
            Model::load(["UserModel"]);
            $user = new UserModel($data);
            $user = $user->isAuthenticated();
            if ($user !== FALSE) {
                Authorization::authorizeUser($user->getId());
                //return response
                Response::ok(["message" => "Login was successfull!! In 3 sec you will be redirected to product/preview/1 page"]);
            }
            Response::error("Login was not successfull");
        } catch (Exception $ex) {
            Response::error("Error occured during registration: " . $ex->getMessage());
        }
    }

}
