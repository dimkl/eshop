<?php

/*
 * Support function to get only public properties of object
 * 
 * @param object $obj Obj is an instance of a class
 * @return array Returns array with the properties of object as values
 */

function get_public_object_vars($obj) {
    return get_object_vars($obj);
}

/**
 * ModelType is a abstract class used as enumeration for the Model types
 *
 * @author dimkl
 */
abstract class ModelType {

    const USER = 1;
    const PRODUCT = 2;
    const COMMENT = 3;
    const RATING = 4;

}

/**
 * ModelException is a class that extend Exception class to provide exception 
 * messages for Model exceptions
 *
 * @author dimkl
 */
class ModelException extends Exception {
    
}

/**
 * ModelFactoryException is a class that extend Exception class to provide exception 
 * messages for Model factory exceptions
 */
class ModelFactoryException extends Exception {
    
}

/**
 * IModel interface
 *
 * @author dimkl
 */
interface IModel {

    /**
     * 
     */
    function setupConnection(IDatabaseProvider $database);

    /**
     * 
     */
    static function getTable();

    /**
     * 
     */
    static function all();

    /**
     * 
     * @param type $columnName
     * @param type $columnValue
     */
    static function find($columnName, $columnValue);

    /**
     * 
     * @param type $id
     */
    static function findById($id);

    /**
     * 
     */
    function create();

    /**
     * 
     */
    function insertSerialization();
}

/**
 * Description of Model
 *
 * @author dimkl
 */
interface IModelFactory {

    /**
     * 
     * @param IDatabaseProvider $database
     */
    static function initFactory(IDatabaseProvider $database);

    /**
     * 
     * @param type $modelType
     */
    static function factory($modelType);
}

/**
 * Description of Model
 *
 * @author dimkl
 */
class Model implements IModel, IModelFactory {

    private static $_table;
    protected static $_database;
    protected $_properties = [];
    protected $_cache = [];

    protected function __construct() {
        
    }

    /**
     * 
     * @param IDatabaseProvider $database
     */
    static function initFactory(IDatabaseProvider $database) {
        static::$_database = $database;
    }

    /**
     * 
     * @param type $modelType
     * @return \UserModel|\CommentModel|\RatingModel|\ProductModel
     * @throws ModelFactoryException
     * @throws ModelException
     */
    static function factory($modelType) {
        if (!is_int($modelType)) {
            throw new ModelFactoryException("ModelType is not a valid one.");
        }
        if (!isset(static::$_database)) {
            throw new ModelFactoryException("Factory must be initiated befoce call");
        }
        if ($modelType === ModelType::USER) {
            $model = new UserModel();
        } else if ($modelType === ModelType::PRODUCT) {
            return new ProductModel(static::$_database);
        } else if ($modelType === ModelType::COMMENT) {
            return new CommentModel(static::$_database);
        } else if ($modelType === ModelType::RATING) {
            return new RatingModel(static::$_database);
        } else {
            throw new ModelException("Model type defined in Model.factory method is not valid.");
        }
        $model->setupConnection(static::$_database);

        return $model;
    }

    /**
     * 
     * @param IDatabaseProvider $database
     */
    public function setupConnection(IDatabaseProvider $database) {
        static::$_database = $database;
    }

    /**
     * 
     * @return type
     */
    public static function getTable() {
        return self::$_table;
    }

    /**
     * 
     * @return type
     * @throws ModelException
     */
    static function all() {
        $table = static::getTable();

        if (!is_string($table)) {
            throw new ModelException("Table must be defined in order to call all.");
        }

        static::$_database->setFetchClassMode(get_called_class());

        return static::$_database->select($table);
    }

    /**
     * Find rows that match the "columnName" of the table with the specified "columnValue"
     * 
     * @param type $columnName
     * @param type $columnValue
     * @throws ModelException
     */
    static function find($columnName, $columnValue) {
        if (!is_string($columnName)) {
            throw new ModelException(' Column name must be type of string');
        }
        if (!is_string($columnValue) && !is_numeric($columnValue) &&
                is_bool($columnValue)) {
            throw new ModelException(' Column value must be type of string, numeric or bool');
        }

        $table = static::getTable();
        if (!is_string($table)) {
            throw new ModelException("Table must be defined in order to call all.");
        }

        static::$_database->setFetchClassMode(get_called_class());
        try {
            $models = static::$_database->select($table, ["query" => $columnName . "=?", "values" => [$columnValue]]);
            return $models;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Find by id is a method to find model from database via "id" column value.
     *  The model should be only one.
     * 
     * @param string|int $id
     * @return IModel Returns Model or null
     * @throws ModelException
     */
    static function findById($id) {
        if (!is_numeric($id) && !is_string($id)) {
            throw new ModelException(' Column value is not valid. Only string or numeric values are accepted');
        }
        $table = static::getTable();
        if (!is_string($table)) {
            throw new ModelException("Table must be defined in order to call all.");
        }

        static::$_database->setFetchClassMode(get_called_class());

        try {
            $models = static::$_database->select($table, ["query" => "id=?", "values" => [$id]]);
            if (count($models) === 1) {
                return $models[0];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return null;
    }

    /**
     * 
     * @param type $columnName
     * @param type $columnValue
     * @param type $join
     * @return type
     * @throws ModelException
     */
    static function findWithJoin($columnName, $columnValue, $join) {
        if (!is_string($columnName)) {
            throw new ModelException(' Column name must be type of string');
        }
        if (!is_string($columnValue) && !is_numeric($columnValue) && is_bool($columnValue)) {
            throw new ModelException(' Column value must be type of string, numeric or bool');
        }

        $table = static::getTable();
        if (!is_string($table)) {
            throw new ModelException("Table must be defined in order to call all.");
        }

        static::$_database->setFetchClassMode(get_called_class());
        try {
            $models = static::$_database->select($table, ["query" => $columnName . "=?", "values" => [$columnValue]], [], $join);
            foreach ($models as $index => $model) {
                $properties = get_public_object_vars($model);
                if (count($properties) > 0) {
                    $cacheKey = strtolower(array_keys($join)[0]);
                    $model->_cache[$cacheKey] = $properties;
                    //remove from model the extra properties
                    foreach ($properties as $i => $prop) {
                        unset($model->$i);
                    }
                }
            }
            return $models;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Get Properties method is a method to get all not null values from the model 
     *  and return them as associative array
     * @return array Returns associative array with attribute as keys and values
     *  as values.
     * @throws Exception
     */
    public function insertSerialization() {
        try {
            $properties = [];
            foreach ($this->_properties as $k) {
                //construct getter method naming
                $k = "get" . strtoupper(substr($k, 0, 1)) . substr($k, 1);
                //get value via calling the method
                $val = call_user_func([$this, $k]);
                if (!is_null($val)) {
                    $properties[$k] = $this->$k;
                }
            }
            return $properties;
        } catch (Exception $ex) {
            throw new Exception("Missing getter method from model." + $ex->getMessage());
        }
    }

    /**
     * Create a row in the database with the values of the model
     * 
     * @return bool Returns true if creation has completed succefully
     * @throws Exception
     */
    public function create() {
        try {
            $_properties = $this->insertSerialization();
            static::$_database->insert(static::getTable(), $_properties);
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }

}
