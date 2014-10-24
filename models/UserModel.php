<?php

/**
 * UserModel is a model class that is used to process data from and to table 'User'
 *
 * @author dimkl
 */
class UserModel extends Model {

    /**
     * @see Model 
     */
    protected static $table = 'User';

    /**
     * @see Model 
     */
    protected $publicProperties = ['id', 'email', 'firstname', 'lastname'];

    /**
     * id table column
     * @var int 
     */
    private $id;

    /**
     * email table column
     * @var string 
     */
    private $email;

    /**
     * password table column
     * @var string 
     */
    private $password;

    /**
     * firstname table column
     * @var string 
     */
    private $firstname;

    /**
     * lastname table column
     * @var string 
     */
    private $lastname;

    /**
     * isAuthenticated method checks if user instance is authenticated and returns
     *  usermodel form database if it is authenticated or false if not
     * @return boolean|UserModel 
     */
    public function isAuthenticated() {
        try {
            $userAssocs = static::$db->setQuerySql('Select id, email,firstname,lastname from User where email=? and password=?')
                    ->setQueryData([$this->getEmail(), $this->getPassword()])
                    ->executeSelect()
                    ->getResult();
            if (count($userAssocs) !== 1) {
                return FALSE;
            }
            return new UserModel($userAssocs[0]);
        } catch (Exception $ex) {
            throw new ModelException($ex);
        }
    }

    /**
     * Custom Query to insert user for registestration in database
     * 
     * @return boolean
     * @throws ModelException
     */
    public function register() {
        $values = [
            "email" => $this->getEmail(),
            "firstname" => $this->getFirstname(),
            "lastname" => $this->getLastname(),
            "password" => $this->getPassword()
        ];
        $table = static::getTable();

        try {
            return static::$db->setQuerySql('Insert into ' . $table
                                    . '(email,firstname,lastname,password) '
                                    . 'Values(:email,:firstname,:lastname,:password)')
                            ->setQueryData($values)
                            ->executeInsert()
                            ->getResult();
        } catch (Exception $ex) {
            throw new ModelException($ex);
        }
    }

    /**
     * Custom Query to check if authenticated user is allowed to comment the product
     * 
     * @param int $productid
     * @return boolean
     * @throws Exception
     */
    public function allowedComment($productid) {
        if (!is_string($productid) && !is_int($productid)) {
            return false;
        }
        try {
            $result = static::$db->setQuerySql('Select * from Comment where  userid=? and productid=?;')
                    ->setQueryData([$this->id, intval($productid)])
                    ->executeSelect()
                    ->getResult();
            if (count($result) !== 1) {
                return TRUE;
            }
            return FALSE;
        } catch (Exception $ex) {
             throw new ModelException($ex);
        }
    }

    /**
     * Getter method for id
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter method for id
     * 
     * @param int|string $id 
     * @return ProductModel
     * @throws ModelException
     */
    public function setId($id) {
        if (!is_string($id) && !is_int($id)) {
            throw new ModelException('$id must be a int');
        }
        if (is_null($this->id)) {
            $this->id = intval($id);
        }
        return $this;
    }

    /**
     * Getter method for email
     * 
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Setter method for email
     * 
     * @param string $email
     * @return \UserModel
     * @throws ModelException
     */
    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ModelException('$email must be a email type');
        }
        $this->email = $email;
        return $this;
    }

    /**
     * Getter method for password
     * 
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Setter method for password
     * 
     * @param string $password
     * @return \UserModel
     * @throws Exception
     */
    public function setPassword($password) {
        if (!is_string($password)) {
            throw new ModelException('$password must be a string');
        }
        $this->password = Model::hash($password);
        return $this;
    }

    /**
     * Getter method for firstname
     * 
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * Setter method for firstname
     * 
     * @param string $firstname
     * @return \UserModel
     * @throws ModelException
     */
    public function setFirstname($firstname) {
        if (!is_string($firstname)) {
            throw new ModelException('$firstname must be a string');
        }
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Getter method for lastname
     * 
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Setter method for lastname
     * 
     * @param type $lastname
     * @return \UserModel
     * @throws ModelException
     */
    public function setLastname($lastname) {
        if (!is_string($lastname)) {
            throw new ModelException('$lastname must be a string');
        }
        $this->lastname = $lastname;
        return $this;
    }

}
