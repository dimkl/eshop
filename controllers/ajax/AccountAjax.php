<?php

/**
 * Description of AccountAjax
 *
 * @author dimkl
 */
class AccountAjax {

    protected function register() {
        Request::allowHttpMethod(Request::POSTMETHOD);

        $data = Request::getPostData();

        try {
            //insert data to model
            Model::load(["UserModel"]);
            $user = new UserModel($data);
            //save model
            $user->register();
            //return response
            Response::ok(["message" => "Register was successfull!!"]);
        } catch (Exception $ex) {
            Response::error("Error occured during registration: " . $ex->getMessage());
        }
    }

    protected function login() {
        Request::allowHttpMethod(Request::POSTMETHOD);
        $data = Request::getPostData();
        try {
            //insert data to model
            Model::load(["UserModel"]);
            $user = new UserModel($data);
            //save model
            $user->authenticate();
            //return response
            Response::ok(["message" => "Login was successfull!!"]);
        } catch (Exception $ex) {
            Response::error("Error occured during registration: " . $ex->getMessage());
        }
    }

}
