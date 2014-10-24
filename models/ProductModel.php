<?php

/**
 * ProductModel is a model class that is used to process data from and to table 'Product'
 *
 * @author dimkl
 */
class ProductModel extends Model {

    /**
     * @see Model 
     */
    protected static $table = 'Product';

    /**
     * @see Model 
     */
    protected $publicProperties = ['id', 'name', 'description'];

    /**
     * id table column
     * @var int 
     */
    private $id;

    /**
     * name table column
     * @var string 
     */
    private $name;

    /**
     * description table column
     * @var string 
     */
    private $description;

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
     * Getter method for name
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Setter method for name
     * 
     * @param string $name
     * @return \ProductModel
     * @throws Exception
     */
    public function setName($name) {
        if (!is_string($name)) {
            throw new ModelException('$name must be a string');
        }
        $this->name = $name;
        return $this;
    }

    /**
     * Getter method for description
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Setter method description
     * 
     * @param string $description
     * @return \ProductModel
     * @throws ModelException
     */
    public function setDescription($description) {
        if (!is_string($description)) {
            throw new ModelException('$description must be a string');
        }
        $this->description = $description;
        return $this;
    }

}
