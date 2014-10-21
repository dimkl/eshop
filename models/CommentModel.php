<?php

/**
 * CommentModel 
 *
 * @author dimkl
 */
class CommentModel extends Model {

    private static $_table = "Comment";
    //
    private $id;
    private $userid;
    private $productid;
    private $content;
    private $rating;
    private $creationDatetime;
    //
    protected $_properties = ["id", "userid", "productid", "content", "creationDatetime"];
    //
    protected $_cache = [];

    public function __construct() {
        
    }

    /**
     * Getter of table name that is source of this model
     * 
     * @return string
     */
    public static function getTable() {

        return self::$_table;
    }

    /**
     * Setter of id
     * 
     * @param int $id 
     * @return \CommentModel Cascading pattern implemenented
     * @throws Exception
     */
    public function setId($id) {
        if (!is_int($id)) {
            throw new Exception("Id must be of int type");
        }
        $this->id = $id;

        return $this;
    }

    /**
     * Getter of id
     * 
     * @return int Returns id value
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter of Userid
     * 
     * @param int $userid
     * @return \CommentModel Cascading pattern implemenented 
     * @throws Exception
     */
    public function setUserid($userid) {
        if (!is_int($userid)) {
            throw new Exception("Userid must be of int type");
        }
        $this->userid = $userid;

        return $this;
    }

    /**
     * Getter of userid
     * 
     * @return int
     */
    public function getUserid() {
        return $this->userid;
    }

    /**
     * Setter of productid
     * 
     * @param int $productid
     * @return \CommentModel Cascading pattern implemenented  
     * @throws Exception
     */
    public function setProductid($productid) {
        if (!is_int($productid)) {
            throw new Exception("Productid must be of int type");
        }
        $this->productid = $productid;

        return $this;
    }

    /**
     * Getter of productid
     * 
     * @return int
     */
    public function getProductid() {
        return $this->productid;
    }

    /**
     * Setter of content
     * 
     * @param string $content
     * @return \CommentModel Cascading pattern implemenented
     * @throws Exception
     */
    public function setContent($content) {
        if (!is_string($content)) {
            throw new Exception("Content must be of string type");
        }
        $this->content = $content;

        return $this;
    }

    /**
     * Getter of content
     * 
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Setter of content
     * 
     * @param string $content
     * @return \CommentModel Cascading pattern implemenented
     * @throws Exception
     */
    public function setRating($rating) {
        if (!is_numeric($rating)) {
            throw new Exception("Rating must be of numeric type");
        }
        
        $this->rating = $rating;

        return $this;
    }

    /**
     * Getter of content
     * 
     * @return string
     */
    public function getRating() {
        return $this->rating;
    }

    /**
     * Getter of creationDatetime
     * 
     * @return string Returns a datetime string 
     */
    public function getCreationDatetime() {
        return $this->creationDatetime;
    }

    /**
     * Getter method to fetch the User model via the userid
     * 
     * @return UserModel Returns the UserModel that is connected via foreign key
     * @throws Exception
     */
    public function getUser() {
        if (!isset($this->_cache["user"])) {
            $userModels = UserModel::findById($this->getUserid());
            if (count($userModels) !== 1) {
                throw new Exception("User model number is not what is expected.");
            }
            $this->_cache["user"] = $userModels[0];
        } else if (is_array($this->_cache["user"])) {
            $user = new UserModel;
            foreach ($this->_cache["user"] as $property => $value) {
                $setter = "set" . strtoupper(substr($property, 0, 1)) . substr($property, 1);
                $user->$setter($value);
            }
            $this->_cache["user"] = $user;
        }

        return $this->_cache["user"];
    }

    public function getProduct() {
        if (!isset($this->_cache["product"])) {
            $productModels = ProductModel::findById($this->getUserid());
            if (count($productModels) !== 1) {
                throw new Exception("Product model number is not what is expected.");
            }
            $this->_cache["product"] = $productModels[0];
        } else if (is_array($this->_cache["product"])) {
            $product = new ProductModel;
            foreach ($this->_cache["product"] as $property => $value) {
                $setter = "set" . strtoupper(substr($property, 0, 1)) . substr($property, 1);
                $product->$setter($value);
            }
            $this->_cache["product"] = $product;
        }

        return $this->_cache["product"];
    }

}
