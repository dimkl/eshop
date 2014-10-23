<?php

interface IModel {

    static function findById($id);

    static function findBy($column, $value);

    static function getTable();

    public function exportToArray();
}

interface IModelFactory {

    static function init(IDatabaseProvider $db);

    static function load($modelsArray);
}

/**
 * Description of Model
 *
 * @author dimkl
 */
class Model implements IModel, IModelFactory {

    const MODELBASEPATH = "./models/";

    public $errorMessages = [];
    protected static $table = '';
    protected $publicProperties = [];
    protected static $db;

    public function __construct($dataArray = []) {
        if (!is_array($dataArray)) {
            throw new Excpetion('Data supplied at Model constructor must be array.');
        }
        $isAssoc = array_keys($dataArray) !== range(0, count($dataArray) - 1);
        if (!$isAssoc && count($dataArray) !== 0) {
            throw new Exception('Data supplied at Model constructor must be associative array.');
        }
        foreach ($dataArray as $name => $value) {
            $this->setter($name, $value);
        }
    }

    public static function hash($string) {
        return hash('sha512', $string);
    }

    static function getTable() {
        return static::$table;
    }

    public static function findById($id) {
        $table = static::getTable();
        try {
            $statement = static::$db->pdo->prepare('Select * from ' . $table . ' where id=?');
            $statement->execute([intval($id)]);
            $result = $statement->fetchObject(get_called_class());
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function findBy($column, $value) {
        $table = static::getTable();

        if (!is_string($column) && !is_numeric($value) && !is_string($value)) {
            return [];
        }
        try {
            $statement = static::$db->pdo->prepare('Select * from ' . $table . ' where `' . $column . '`=?;');
            $statement->execute([$value]);
            return $statement->fetchAll(PDO::FETCH_CLASS, get_called_class());
        } catch (Exception $e) {
            return [];
        }
    }

    public static function init(IDatabaseProvider $db) {
        static::$db = $db;
    }

    public static function load($modelsArray) {
        if (!is_array($modelsArray)) {
            throw new Excpetion('$modelsArray supplied at Model constructor must be array.');
        }
        $isAssoc = array_keys($modelsArray) !== range(0, count($modelsArray) - 1);
        if ($isAssoc) {
            throw new Exception('$modelsArray supplied at Model constructor must not be associative array.');
        }
        try {
            foreach ($modelsArray as $modelName) {
                $modelFile = Model::MODELBASEPATH . $modelName . '.php';
                if (!file_exists($modelFile)) {
                    throw new Exception("Model class with name " . $modelName . " was not found");
                }
                include $modelFile;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    protected function setter($name, $value) {
        $setter = 'set' . ucfirst($name);
        try {
            if (!method_exists($this, $setter)) {
                throw new Exception('Method' . $name . ' supplied at Model setter does not exist.');
            }
            $this->$setter($value);
        } catch (Exception $ex) {
            array_push($this->errorMessages, $ex->getMessage());
        }
    }

    protected function getter($name) {
        $getter = 'get' . ucfirst($name);
        try {
            if (!method_exists($this, $getter)) {
                throw new Exception('Method' . $name . ' supplied at Model getter does not exist.');
            }
            return $this->$getter();
        } catch (Exception $ex) {
            array_push($this->errorMessages, $ex->getMessage());
        }
    }

    public function exportToArray() {
        $properties = [];
        foreach ($this->publicProperties as $name) {
            try {
                $prop = $this->getter($name);
                
                if ($prop instanceof Model) {
                    $prop = $prop->exportToArray();
                }
                if (!is_null($prop)) {
                    $properties[$name] = $prop;
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
                array_push($this->errorMessages, $ex->getMessage());
                throw $ex;
            }
        }

        return $properties;
    }

}
