<?php

/**
 * Description of ProductModel
 *
 * @author dimkl
 */
class ProductModel extends Model {

    protected static $table = 'Product';
    protected $publicProperties = ['id', 'name', 'description'];
    private $id;
    private $name;
    private $description;

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

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        if (!is_string($name)) {
            throw new Exception('$name must be a string');
        }
        $this->name = $name;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        if (!is_string($description)) {
            throw new Exception('$description must be a string');
        }
        $this->description = $description;
        return $this;
    }

}
