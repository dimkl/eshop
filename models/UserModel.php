<?php

/**
 * Description of UserModel
 *
 * @author dimkl
 */
class UserModel extends Model {

    protected static $table = 'User';
    protected $publicProperties = ['id', 'email', 'firstname', 'lastname'];
    private $id;
    private $email;
    private $password;
    private $firstname;
    private $lastname;

    public function authenticate() {
        try {
            $table = static::getTable();
            $statement = static::$db->pdo->prepare('Select email, password from ' . $table . ' where email=? and password=?');
            $statement->execute([$this->getEmail(), $this->getPassword()]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if ($result !== FALSE) {
                return TRUE;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return FALSE;
    }

    public function register() {
        $values = [
            "email" => $this->getEmail(),
            "firstname" => $this->getFirstname(),
            "lastname" => $this->getLastname(),
            "password" => $this->getPassword()
        ];
        $table = static::getTable();

        try {
            $statement = static::$db->pdo->prepare('Insert into '
                    . $table . '(email,firstname,lastname,password) '
                    . 'Values(:email,:firstname,:lastname,:password)');
            return $statement->execute($values);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        if (!is_string($id) && !is_int($id)) {
            throw new Exception('$id must be a int');
        }
        if (is_null($this->id)) {
            $this->id = intval($id);
        }
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('$email must be a email type');
        }
        $this->email = $email;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        if (!is_string($password)) {
            throw new Exception('$password must be a string');
        }
        $this->password = Model::hash($password);
        return $this;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        if (!is_string($firstname)) {
            throw new Exception('$firstname must be a string');
        }
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        if (!is_string($lastname)) {
            throw new Exception('$lastname must be a string');
        }
        $this->lastname = $lastname;
        return $this;
    }

}
