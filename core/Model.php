<?php

/**
 * ModelException class is used for the Exceptions in Model and all child classes 
 */
class ModelException extends Exception {
    
}

/**
 * IModel interface is used as the guideline of every Model class for typical ORM methods
 */
interface IModel {

    static function findById($id);

    static function findBy($column, $value);

    static function getTable();

    public function exportToArray();
}

/**
 * IModel interface is used as the guideline of every Model class for typical 
 *  factory pattern methods
 */
interface IModelFactory {

    static function init(IDatabaseProvider $db);

    static function load($modelsArray);
}

/**
 * Description of Model
 *
 * @author dimkl
 */
abstract class Model implements IModel, IModelFactory {

    const MODELBASEPATH = "./models/";

    public $errorMessages = [];
    protected static $table = '';

    /**
     * $publicProperties is used in exportToArray method so as to export via the getters 
     *  only the properties defined in this array as a return value
     * 
     * @var array 
     */
    protected $publicProperties = [];

    /**
     * Keeps db connection
     * 
     * @var IDatabaseProvider 
     */
    protected static $db;

    /**
     * 
     * @param type $dataArray
     * @throws ModelException
     */
    protected function __construct($dataArray = []) {
        if (!is_array($dataArray)) {
            throw new ModelException('Data supplied at Model constructor must be array.');
        }
        $isAssoc = array_keys($dataArray) !== range(0, count($dataArray) - 1);
        if (!$isAssoc && count($dataArray) !== 0) {
            throw new ModelException('Data supplied at Model constructor must be associative array.');
        }
        foreach ($dataArray as $name => $value) {
            $this->setter($name, $value);
        }
    }

    /**
     * hash is used to hash password with a predined way
     * 
     * @param string $string
     * @return string
     */
    public static function hash($string) {
        return hash('sha512', $string);
    }

    /**
     * getTable is used to get the private static table attribute that is used in queries
     * in the table definition of sql query.
     * @return string
     */
    static function getTable() {
        return static::$table;
    }

    /**
     * findById is used to fetch model object via the defined id
     * 
     * @param int $id
     * @return Model
     */
    public static function findById($id) {
        $table = static::getTable();
        try {
            $result = static::$db->setQuerySql('Select * from ' . $table . ' where id=?')
                    ->setQueryData([intval($id)])
                    ->setResultClass(get_called_class())
                    ->executeSelect()
                    ->getResult();
            if (count($result) == 1) {
                return $result[0];
            }
            return null;
        } catch (Exception $e) {
            throw new DatabaseException($ex);
        }
    }

    /**
     * findBy is used to fetch array of data using the column and value supplied 
     *  for the sql where part.
     * 
     * @param type $column
     * @param type $value
     * @return array
     */
    public static function findBy($column, $value) {
        $table = static::getTable();

        if (!is_string($column) && !is_numeric($value) && !is_string($value)) {
            return [];
        }
        try {
            return static::$db->setQuerySql('Select * from ' . $table . ' where `' . $column . '`=?;')
                            ->setQueryData([$value])
                            ->setResultClass(get_called_class())
                            ->executeSelect()
                            ->getResult();
        } catch (Exception $ex) {
            throw new DatabaseException($ex);
        }
    }

    /**
     * init is used to initated static attributes of the Model class.
     *  it initiates database connection via the supplied IDatabaseProvider
     * 
     * @param IDatabaseProvider $db
     */
    public static function init(IDatabaseProvider $db) {
        static::$db = $db;
    }

    /**
     * load is used to include multiple model files from the MODELPATH defined
     * 
     * @param array $modelsArray
     * @throws Excepetion
     * @throws Exception
     */
    public static function load($modelsArray) {
        if (!is_array($modelsArray)) {
            throw new ModelExcepetion('$modelsArray supplied at Model constructor must be array.');
        }
        $isAssoc = array_keys($modelsArray) !== range(0, count($modelsArray) - 1);
        if ($isAssoc) {
            throw new ModelException('$modelsArray supplied at Model constructor must not be associative array.');
        }
        try {
            foreach ($modelsArray as $modelName) {
                $modelFile = Model::MODELBASEPATH . $modelName . '.php';
                if (!file_exists($modelFile)) {
                    throw new ModelException("Model class with name " . $modelName . " was not found");
                }
                include $modelFile;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * setter is used to simplify multiple setting of data using their setters
     * 
     * @param string $name
     * @param string $value
     * @throws Exception
     */
    protected function setter($name, $value) {
        $setter = 'set' . ucfirst($name);
        try {
            if (!method_exists($this, $setter)) {
                throw new ModelException('Method' . $name . ' supplied at Model setter does not exist.');
            }
            $this->$setter($value);
        } catch (Exception $ex) {
            array_push($this->errorMessages, $ex->getMessage());
        }
    }

    /**
     * getter is used to simplify multiple getting of data using their getters
     * 
     * @param string $name
     * @param string $value
     * @throws Exception
     */
    protected function getter($name) {
        $getter = 'get' . ucfirst($name);
        try {
            if (!method_exists($this, $getter)) {
                throw new ModelException('Method' . $name . ' supplied at Model getter does not exist.');
            }
            return $this->$getter();
        } catch (Exception $ex) {
            array_push($this->errorMessages, $ex->getMessage());
        }
    }

    /**
     * exportToArray is used to export public properties in array format
     * 
     * @return array
     * @throws Exception
     */
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
