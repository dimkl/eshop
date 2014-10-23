<?php

/**
 * 
 */
abstract class UserType {

    const GUEST = 1;
    const USER = 2;

}

/**
 * 
 */
class SessionManager {

    public static function check() {
        if (!isset($_SESSION['userid'], $_SESSION['userType'], $_SESSION['validationToken'])) {
            return false;
        }
        return true;
    }

    public static function init($userid, $userType) {
        if (!is_string($userid) && !is_int($userid)) {
            throw new Exception('$userid must be int or string type');
        }
        if (!is_string($userType) && !is_int($userType)) {
            throw new Exception('$userType must be int or string type');
        }
        if (!isset($_SESSION['userid'], $_SESSION['userType'], $_SESSION['validationToken'])) {
            $_SESSION['userid'] = $userid;
            $_SESSION['userType'] = $userType;
            $_SESSION['validationToken'] = hash('sha512', date('Y-m-i'));
        } else {
            throw new Exception('Session already initiated');
        }
    }

    public static function destroy() {
        session_destroy();
    }

    public static function getUserId() {
        if (!isset($_SESSION['userid'])) {
            throw new Exception('userid not saved in session');
        }
        return intval($_SESSION['userid']);
    }

    public static function getUserType() {
        if (!isset($_SESSION['userType'])) {
            return UserType::GUEST;
        }
        return intval($_SESSION['userType']);
    }

    public static function getValidationToken() {
        if (!isset($_SESSION['validationToken'])) {
            throw new Exception('validationToken not saved in session');
        }
        return $_SESSION['validationToken'];
    }

    public static function refreshValidationToken() {
        if (!isset($_SESSION['validationToken'])) {
            throw new Exception('validationToken not saved in session');
        }

        $_SESSION['validationToken'] = hash('sha512', date('Y-m-i'));
    }

}

/**
 * Description of Authorization
 *
 * @author dimkl
 */
class Authorization {

    private static $accessList = [
        'navigation-product-preview' => [UserType::GUEST, UserType::USER],
        'navigation-account-register' => [UserType::GUEST, UserType::USER],
        'navigation-account-login' => [UserType::GUEST, UserType::USER],
        'navigation-error-index' => [UserType::GUEST, UserType::USER],
        'navigation-account-logout' => [UserType::USER],
        'ajax-comment-create' => [UserType::USER],
        'ajax-account-register' => [UserType::GUEST, UserType::USER],
        'ajax-account-login' => [UserType::GUEST]
    ];

    public static function check($pageName) {
        if (!is_string($pageName)) {
            throw new Exception("Page name supplied must be a string.");
        }
        if (!in_array($pageName, array_keys(static::$accessList))) {
            throw new Exception("Page name does not exist in authorization list.");
        }
        SessionManager::check();
        $role = SessionManager::getUserType();
        if (!in_array($role, static::$accessList[$pageName])) {
            throw new Exception("User is not authorized for this page");
        }
    }

    public static function getCurrrentUserid() {
        try {
            return SessionManager::getUserId();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public static function authorizeUser($userid) {
        try {
            SessionManager::init(intval($userid), UserType::USER);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public static function unAuthorizeUser() {
        try {
            SessionManager::destroy();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
