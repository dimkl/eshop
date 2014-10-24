<?php

/**
 * SessionManagerException class is an extention to Exception class for 
 *  SessionManager class exceptions
 */
class SessionManagerException extends Exception {
    
}

/**
 * AuthorizationExcepetion class is an extention to Exception class for 
 *  Authorization class exceptions
 */
class AuthorizationExcepetion extends Exception {
    
}

/**
 * interface for guidance of SessionManager class
 */
interface ISessionManager {

    public static function check();

    public static function init($userid, $userType);

    public static function destroy();

    public static function getUserId();

    public static function getUserType();
}

/**
 * interface for guidance of Authorization class
 */
interface IAuthorization {

    public static function allowOnly($accessList);

    public static function ajaxAllowOnly($accessList, $callback = null);

    public static function getCurrrentUserid();

    public static function authorizeUser($userid);

    public static function unAuthorizeUser();
}

/**
 * UserType abstract class for enumerating userType values
 */
abstract class UserType {

    const GUEST = 1;
    const USER = 2;

}

/**
 * SessionManager class is responsible for handling Session data
 */
class SessionManager implements ISessionManager {

    /**
     * check public static method is responsible for checking the session and returns 
     * if it's valid or not
     * 
     * @return boolean
     */
    public static function check() {
        if (!isset($_SESSION['userid'], $_SESSION['userType'], $_SESSION['validationToken'])) {
            return false;
        }
        return true;
    }

    /**
     * init public static method is responsible for intitiating the session
     * 
     * @param int $userid
     * @param int $userType
     * @throws SessionManagerException
     */
    public static function init($userid, $userType) {
        if (!is_string($userid) && !is_int($userid)) {
            throw new SessionManagerException('$userid must be int or string type');
        }
        if (!is_string($userType) && !is_int($userType)) {
            throw new SessionManagerException('$userType must be int or string type');
        }
        if (!isset($_SESSION['userid'], $_SESSION['userType'], $_SESSION['validationToken'])) {
            $_SESSION['userid'] = $userid;
            $_SESSION['userType'] = $userType;
            $_SESSION['validationToken'] = hash('sha512', date('Y-m-i'));
        } else {
            throw new SessionManagerException('Session already initiated');
        }
    }

    /**
     * destroy public static method is responsible for destroying current session
     */
    public static function destroy() {
        session_destroy();
    }

    /**
     * 
     * @return int
     * @throws SessionManagerException
     */
    public static function getUserId() {
        if (!isset($_SESSION['userid'])) {
            throw new SessionManagerException('userid not saved in session');
        }
        return intval($_SESSION['userid']);
    }

    /**
     * 
     * @return int
     */
    public static function getUserType() {
        if (!isset($_SESSION['userType'])) {
            return UserType::GUEST;
        }
        return intval($_SESSION['userType']);
    }

    /**
     * 
     * @return string
     * @throws SessionManagerException
     */
    public static function getValidationToken() {
        if (!isset($_SESSION['validationToken'])) {
            throw new SessionManagerException('validationToken not saved in session');
        }
        return $_SESSION['validationToken'];
    }

    /**
     * refreshValidationToken public static method is responsible for resfreshing a token 
     * that is useful for security from CSRF attacks
     * @throws SessionManagerException
     */
    public static function refreshValidationToken() {
        if (!isset($_SESSION['validationToken'])) {
            throw new SessionManagerException('validationToken not saved in session');
        }

        $_SESSION['validationToken'] = hash('sha512', date('Y-m-i'));
    }

}

/**
 * Authorization class is responsible for handling the Authorization process
 *
 * @author dimkl
 */
class Authorization implements IAuthorization {

    /**
     * ajaxAllowOnly public static method is responsible for handling authorization through accessList
     * for ajax controllers
     * 
     * @param array $accessList
     * @param function $callback
     * @throws AuthorizationExcepetion
     */
    public static function ajaxAllowOnly($accessList, $callback = null) {
        try {
            if (!is_null($callback) && !is_callable($callback)) {
                throw new AuthorizationExcepetion('$callback supplied must be a function.');
            }
            if (!is_array($accessList)) {
                throw new AuthorizationExcepetion('$accessList supplied must be a string.');
            }
            $isAssoc = array_keys($accessList) !== range(0, count($accessList) - 1);
            if ($isAssoc) {
                throw new AuthorizationExcepetion('$accessList supplied at Authorization must "not" be an associative array.');
            }
            SessionManager::check();
            $userType = SessionManager::getUserType();
            if (!in_array($userType, $accessList)) {
                throw new AuthorizationExcepetion('User is not authorized for this page');
            }
        } catch (Exception $ex) {
            if (is_null($callback)) {
                throw new AuthorizationExcepetion($ex);
            }
            $callback($ex);
        }
    }

    /**
     * allowOnly public static method is responsible for handling authorization through accessList check 
     * for navigation controllers 
     * 
     * @param array $accessList
     * @param function $callback
     * @throws AuthorizationExcepetion
     */
    public static function allowOnly($accessList, $callback = null) {
        try {
            if (!is_null($callback) && !is_callable($callback)) {
                throw new AuthorizationExcepetion('$callback supplied must be a function.');
            }
            if (!is_array($accessList)) {
                throw new AuthorizationExcepetion('$accessList supplied must be a string.');
            }
            $isAssoc = array_keys($accessList) !== range(0, count($accessList) - 1);
            if ($isAssoc) {
                throw new AuthorizationExcepetion('$accessList supplied at Authorization must "not" be an associative array.');
            }
            SessionManager::check();
            $userType = SessionManager::getUserType();
            if (!in_array($userType, $accessList)) {
                throw new AuthorizationExcepetion('User is not authorized for this page');
            }
        } catch (Exception $ex) {
            if (!is_null($callback)) {
                $callback($ex);
            }
            header('Location: ' . APPLICATIONFOLDER . 'navigation/error/forbidden');
        }
    }

    /**
     * getCurrrentUserid public static method is responsible for getting the current user id 
     * or null value if the user is not logged in
     * 
     * @return int|null
     */
    public static function getCurrrentUserid() {
        try {
            return SessionManager::getUserId();
        } catch (Exception $ex) {
            return NULL;
        }
    }

    /**
     * authorizeUser public static method is responsible for authorizing a user to system
     * 
     * @param int $userid
     * @throws AuthorizationExcepetion
     */
    public static function authorizeUser($userid) {
        try {
            SessionManager::init(intval($userid), UserType::USER);
        } catch (Exception $ex) {
            throw new AuthorizationExcepetion($ex);
        }
    }

    /**
     * unAuthorizeUser public static method is responsible for unAuthorizing a user to system
     * 
     * @throws AuthorizationExcepetion
     */
    public static function unAuthorizeUser() {
        try {
            SessionManager::destroy();
        } catch (Exception $ex) {
            throw new AuthorizationExcepetion($ex);
        }
    }

}
