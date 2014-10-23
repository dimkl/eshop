<?php

/**
 * 
 */
abstract class UserType {

    const GUEST = 1;
    const USER = 0;

}

/**
 * 
 */
class SessionManger {
    
}

/**
 * Description of Authorization
 *
 * @author dimkl
 */
class Authorization {

    private static $accessList = [
        "productcontroller-preview" => [UserType::GUEST, UsetType::USER],
        "accountcontroller-register" => [UserType::GUEST, UsetType::USER],
        "accountcontroller-login" => [UserType::GUEST, UsetType::USER],
        "commentajax-create" => [UsetType::USER],
        "accountajax-register" => [UserType::GUEST, UsetType::USER],
        "accountajax-login" => [UserType::GUEST],
    ];

    public static function check() {
        return 0;
    }

    public static function getCurrrentUserid() {
        return 0;
    }

}
