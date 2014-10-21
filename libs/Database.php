<?php

/**
 * SqlValidator is a class that validates part of an sql query 
 * 
 */
Class SqlValidator {

    /**
     * Validates $columns parameter and converts it to string for pdo statement
     * @param array $columns Columns must be a simple array  of strings
     * @return string
     * @throws Exception if validator does not match the criteria
     */
    static function validateColumns($columns) {
        if (!is_array($columns)) {
            throw new Exception('Columns must be an array.');
        }
        $isAssocArray = array_keys($columns) !== range(0, count($columns) - 1);
        if ($isAssocArray && count($columns) !== 0) {
            throw new Exception('Columns must be simple array.');
        }
        //columns checks
        if (count($columns) === 0) {
            $columns = "*";
        } else {
            $columns = implode($columns, ',');
        }
        return $columns;
    }

    /**
     * Validates $where parameter and converts it to string for pdo statement
     * @param array $where Where must be an associative array with structure:
     *  ["query"=>"columnname=?","values"=[""]]
     * @return array Returns the input valid array or array with default 
     *  structure: ["query"=>"","values"=[""]]
     * @throws Exception if validator does not match the criteria
     */
    static function validateWhere($where) {
        if (!is_array($where)) {
            throw new Exception('Where must be an array.');
        } else if (count($where) !== 0) {
            $isAssocArray = array_keys($where) !== range(0, count($where) - 1);
            if (!$isAssocArray) {
                throw new Exception('Where must be an associative array.');
            } else if (!isset($where["query"]) || !isset($where["values"])) {
                throw new Exception('Where must be an associative array with "query" and "values" keys.');
            } else if (!is_string($where["query"]) || !is_array($where["values"])) {
                throw new Exception('Where must be an associative array with "query" as string and "values" as array.');
            }

            $matchedValues = preg_match_all("/^[a-z\_]+=[?] ?(( (and)|(or))? [a-z\_]+=[?])*$/", $where["query"]);
            if ($matchedValues === 0) {
                throw new Exception('Where "query" must match the format of pdo prepare "columnname=?"');
            } else if ($matchedValues !== count($where["values"])) {
                throw new Exception('Where "query" values must match the number of "values" inserted.');
            }
        } else {
            return ["query" => "", "values" => [""]];
        }
        $where["query"] = "where " . $where["query"];
        return $where;
    }

    /**
     * Validates $table parameter to be a string
     * @param string Table must be a string
     * @return string
     * @throws Exception if validator does not match the criteria
     */
    static function validatTable($table) {
        if (!is_string($table)) {
            throw new DatabaseParameterTypeException('Table must be a string.');
        }
        return $table;
    }

    /**
     * Validates $values of insert data to be associative array of "key" to be
     *  the columnname of table and "value" of the value to be inserted.
     *
     * @param string $data Data must be an associative array with structure: 
     *  ["columnname"=>"value"]
     * @return array Returns associative array with structure:
     *  ["tableColumns"=>"(...)","valuesColumns"=>"?,...","values"=>[])
     * @throws Exception if validator does not match the criteria
     */
    static function validateInsert($data) {
        if (!is_array($data)) {
            throw new Exception('Data must be an array.');
        }
        $isAssocArray = array_keys($data) !== range(0, count($data) - 1);
        if (!$isAssocArray) {
            throw new Exception('Data must be an associative array.');
        }

        $defaultData = [
            "tableColumns" => "",
            "valuesColumns" => "",
            "values" => [],
        ];
        try {
            $returnData = $defaultData;
            $returnData["tableColumns"] = '(' . join(',', array_keys($data)) . ')';
            $returnData["valuesColumns"] = implode(array_fill(0, count($data), '?'), ',');
            $returnData["values"] = array_values($data);

            return $returnData;
        } catch (Exception $ex) {
            return $defaultData;
        }
    }

    /**
     * Validate $join parameter to be associative array of structure:
     *  ["tablename"=>"table.column=table2.column2"]
     * @param array $join 
     * @return string Returns string with the format of sql join
     */
    static function validateJoin($join) {
        if (!is_array($join)) {
            throw new Exception('Join must be an array.');
        } else if (count($join) > 0) {
            $isAssocArray = array_keys($join) !== range(0, count($join) - 1);
            if (!$isAssocArray) {
                throw new Exception('Join must be an associative array.');
            }
            if (count($join) > 1) {
                throw new Exception('Join must be an associative array of length 1.');
            }
            $matchValue = strtolower(array_values($join)[0]);
            if (preg_match_all("/^[a-z\_\.]+\=[a-z\_\.]+$/", $matchValue) === 0) {
                throw new Exception('Join "on" value is not valid.');
            }
        } else {
            return "";
        }
        return "JOIN " . array_keys($join)[0] . ' on ' . array_values($join)[0];
    }

}

/**
 * Database Exception extension for handling message for database errors
 */
class DatabaseException extends Exception {
    
}

/**
 * Database Parameter Type Exception extension for handling message 
 *  for database parameters errors
 */
class DatabaseParameterTypeException extends Exception {
    
}

/**
 * Interface for compatibility among Database connection implementation
 * 
 */
interface IDatabaseProvider {

    function select($table, $columns = [], $where = []);

    function insert($table, $data);
}

/**
 * Database class is responsible to handle the connection and queries to database
 *
 */
class DatabaseProvider implements IDatabaseProvider {

    /**
     * PDO connection
     * @var object 
     */
    private $pdo;

    /**
     * fetchClass is charate
     * @var string 
     */
    private $fetchClass;

    /**
     * Constructor used to create a persistant database connection
     * 
     * @param string $hostname Host name to use 
     * @param string $username User name to use
     * @param string $password Password for the username 
     * @param string $database Database to use
     */
    public function __construct($driver, $hostname, $port, $username, $password, $database) {
        try {
            $this->pdo = new PDO("{$driver}:host={$hostname};port={$port};dbname={$database}", $username, $password);
        } catch (DatabaseException $ex) {
            throw $ex;
        }
    }

    /**
     * Select method used to select data from database
     * 
     * @param string $table Table to select columns.
     * @param array $columns Columns to be selected from table. If empty it will use "*".
     * @param array $where Where conditions. If empty it will select all.
     * @return array Returns rows of data found for the query made
     * @throws DatabaseParameterTypeException if table or columns aren't of the correct type
     * @throws DatabaseException if error occurs from the database
     */
    public function select($table, $where = [], $columns = [], $join = []) {

        try {
            $table = SqlValidator::validatTable($table);
            $columns = SqlValidator::validateColumns($columns);
            $where = SqlValidator::validateWhere($where);
            $join = SqlValidator::validateJoin($join);
        } catch (Exception $ex) {
            throw new DatabaseParameterTypeException($ex->getMessage());
        }
        try {
            $_statement = $this->pdo->prepare('Select ' . $columns . ' from ' . $table . ' ' . $join . ' ' . $where['query'] . ' ;');
            $_statement->execute($where['values']);
            if (!is_null($this->fetchClass)) {
                $_statement->setFetchMode(PDO::FETCH_CLASS, $this->fetchClass);
                $this->fetchClass = null;
            } else {
                $_statement->setFetchMode(PDO::FETCH_ASSOC);
            }
            return $_statement->fetchAll();
        } catch (Exception $ex) {
            throw new DatabaseException($ex->getMessage());
        }
    }

    /**
     * Insert method used to insert data from database.
     * 
     * @param string $table Table to select columns.
     * @param array $data Data must be an associative array ["columnName"=>"data"]
     * @return void 
     * @throws TypeException if table or data aren't of the correct type
     * @throws DatabaseException if error occurs from the database
     */
    public function insert($table, $data) {
        try {
            $table = SqlValidator::validatTable($table);
            $data = SqlValidator::validateInsert($data);
        } catch (Exception $ex) {
            throw new DatabaseParameterTypeException($ex->getMessage());
        }
        try {
            $_statement = $this->pdo->prepare('Insert into ' . $table . ' ' . $data["tableColumns"] . ' Values (' . $data["valuesColumns"] . ');');
            var_dump($data["values"]);
            $_statement->execute($data["values"]);
        } catch (Exception $ex) {
            throw new DatabaseException($ex->getMessage());
        }
    }

    /**
     * 
     */
    public function setFetchClassMode($className) {
        if (!is_string($className)) {
            throw new DatabaseException("Set fetch class mode expects class name parameter of type string.");
        }
        $this->fetchClass = $className;
    }

}
