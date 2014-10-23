<?php

/**
 * Database Exception extension for handling message for database errors
 */
class DatabaseException extends Exception {
    
}

/**
 * Database Parameter Type Exception extension for handling message 
 *  for database parameters errors
 */
class DatabaseParameterTypeException extends Exception {
    
}

interface IDatabaseProvider {
    
}

/**
 * Database class is responsible to handle the connection and queries to database
 *
 */
class DatabaseProvider implements IDatabaseProvider {

    public $pdo;

    public function __construct($driver, $hostname, $port, $username, $password, $database) {
        try {
            $this->pdo = new PDO("{$driver}:host={$hostname};port={$port};dbname={$database}", $username, $password);
        } catch (DatabaseException $ex) {
            throw $ex;
        }
    }

//    public function authenticatedUser($username, $password) {
//        try {
//            $statement = $this->pdo->prepare("Select * from User where email=? and password=?");
//            $statement->execute([$username, Model::hash($password)]);
//            $user = $statement->fetchObject("UserModel");
//            if ($user !== FALSE) {
//                return true;
//            }
//        } catch (Exception $e) {
//            return false;
//        }
//    }
//
//    public function getUser($userid) {
//        try {
//            $userid = intval($userid);
//
//            $statement = $this->pdo->prepare("Select * from User where id=?");
//            $statement->execute([$userid]);
//            return $statement->fetchObject("UserModel");
//        } catch (Exception $e) {
//            return null;
//        }
//    }
//
//    public function createUser($data) {
//
//        try {
//            if (!is_array($data)) {
//                return false;
//            }
//            $valueMarks = array_fill(0, count($data), "?");
//            $statement = $this->pdo->prepare("Insert Into User Values({$valueMarks})");
//            return $statement->execute($data);
//        } catch (Exception $e) {
//            return false;
//        }
//    }
//
//    public function getProduct($productid) {
//        try {
//            $productid = intval($productid);
//            $statement = $this->pdo->prepare("Select * from Product where id=?");
//            $statement->execute([$productid]);
//            return $statement->fetchObject("ProductModel");
//        } catch (Exception $e) {
//            return null;
//        }
//    }
//
//    public function getCommentsWithUsersByProductId($productid) {
//        try {
//            $productid = intval($productid);
//            $statement = $this->pdo->prepare("Select * from Comment join User on User.id=Comment.userid where productid=?");
//            $statement->execute([$productid]);
//            return $statement->fetchAll(PDO::FETCH_CLASS, "CommentUserModel");
//        } catch (Exception $e) {
//            return null;
//        }
//    }
//
//    public function createComment($data) {
//        try {
//            if (!is_array($data)) {
//                return false;
//            }
//            $valueMarks = array_fill(0, count($data), "?");
//            $statement = $this->pdo->prepare("Insert Into Comment Values({$valueMarks})");
//            return $statement->execute($data);
//        } catch (Exception $e) {
//            return false;
//        }
//    }

}
