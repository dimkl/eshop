<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserModel
 *
 * @author dimkl
 */
class UserModel extends Model {

    private static $_table = "User";
    private $id;
    private $email;
    private $password;
    private $firstname;
    private $lastname;
//
    protected $_properties = ["id", "email", "password", "firstname", "lastname"];

    public function __construct() {
    }

    public static function getTable() {

        return self::$_table;
    }

    public function setId($id) {
        if (!is_int($id)) {
            throw new Exception("Id must be of int type");
        }
        $this->id = $id;

        return $this;
    }

    public function getId() {
        return $this->email;
    }

    public function setEmail($email) {
        if (!is_string($email)) {
            throw new Exception("Email must be of string type");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email format is not valid");
        }

        $this->email = $email;

        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        if (!is_string($password)) {
            throw new Exception("Password must be of string type");
        }
        if (strlen($password) < 6) {
            throw new Exception("Password must be more than 6 characters");
        }
        $this->password = $password;

        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setFirstname($firstname) {
        if (!is_string($firstname)) {
            throw new Exception("Firstname must be of string type");
        }
        $this->firstname = $firstname;

        return $this;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setLastname($lastname) {
        if (!is_string($lastname)) {
            throw new Exception("Lastname must be of string type");
        }
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastname() {
        return $this->lastname;
    }

}
