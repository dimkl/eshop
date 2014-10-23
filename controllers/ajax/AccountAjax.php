<?php

include './libs/Request.php';
include './libs/Response.php';

/**
 * Description of AccountAjax
 *
 * @author dimkl
 */
class AccountAjax extends Controller {

    protected function register() {
        Request::allowHttpMethod(HttpMethods::POST);

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
                Response::ok(["message" => "Register was successfull!!"]);
            }
            Response::error("Registration was not successfull." . implode('<br/>', $user->errorMessages));
        } catch (Exception $ex) {
            Response::error("Error occured during registration: " . $ex->getMessage());
        }
    }

    protected function login() {
        Request::allowHttpMethod(HttpMethods::POST);
        $data = Request::getPostData();
        try {
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
