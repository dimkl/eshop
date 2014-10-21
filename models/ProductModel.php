<?php

/**
 * Description of ProductModel
 *
 * @author dimkl
 */
class ProductModel extends Model {

    private static $_table = "Product";
    private $id;
    private $name;
    private $description;
    //
    protected $_properties = ["id", "name", "description"];

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
     * @return \ProductModel Cascading pattern implemenented
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
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter of name
     * 
     * @param string $name
     * @return \ProductModel Cascading pattern implemenented
     * @throws Exception
     */
    public function setName($name) {
        if (!is_string($name)) {
            throw new Exception("Name must be of string type");
        }
        $this->name = $name;

        return $this;
    }

    /**
     * Getter of name
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Setter of description
     * 
     * @param type $description
     * @return \ProductModel Cascading pattern implemenented
     * @throws Exception
     */
    public function setDescription($description) {
        if (!is_string($description)) {
            throw new Exception("Description must be of string type");
        }
        $this->description = $description;

        return $this;
    }

    /**
     * Getter of description
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

}
