<?php

/**
 * Database Exception extension for handling message for database errors
 */
class DatabaseException extends Exception {
    
}

interface IDatabaseProvider {

    /**
     * execute select query type
     * 
     * @return IDatabaseProvider Returns IDatabaseProvider object for cascadind style implementation
     */
    function executeSelect();

    /**
     * execute insert query type
     * @return IDatabaseProvider Returns IDatabaseProvider object for cascadind style implementation
     */
    function executeInsert();

    /**
     * execute update query type
     * @return IDatabaseProvider Returns IDatabaseProvider object for cascadind style implementation
     */
    function executeUpdate();

    /**
     * execute delete query type
     * @return IDatabaseProvider Returns IDatabaseProvider object for cascadind style implementation
     */
    function executeDelete();

    /**
     * set query data to be used in execution
     * @return IDatabaseProvider Returns IDatabaseProvider object for cascadind style implementation
     */
    function setQueryData($data);

    /**
     * set sql query to be prepared for execution
     * @return IDatabaseProvider Returns IDatabaseProvider object for cascadind style implementation
     */
    function setQuerySql($sql);

    /**
     * set Fetch class for only the next query so as to transform the data
     * 
     * @param string $className
     */
    function setResultClass($className);

    /**
     * get Result from executed query
     * 
     * @return boolean|array Returns bollean for update, delete, insert and 
     *  array for select queries
     */
    function getResult();

    /**
     * Get last inserted id
     * @return int Returns id of last insertid element
     */
    function getLastInsertId();
}

/**
 * Database class is responsible to handle the connection and queries to database
 *
 */
class DatabaseProvider implements IDatabaseProvider {

    /**
     * Keeps the connection to database
     * @var PDO 
     */
    private $pdo;

    /**
     * Keeps pdo statement for later call
     * @var PDO::Statement 
     */
    private $statement;
    //query variables
    /**
     * Keeps query results
     * @var string 
     */
    private $queryResult = FALSE;

    /**
     * Keeps query data to be used on execute
     * 
     * @var string 
     */
    private $queryData = [];

    /**
     * Keeps fetch Class to be used on fetch
     * @var string 
     */
    private $fetchClass = '';

    /**
     * Keeps query cache as array with sql query as key and query results as values
     * 
     * @var array 
     */
    private $cache = [];

    /**
     * Keeps query cache for 
     * @var type 
     */
    private $cacheStartTime;

    /**
     * Keeps cache duration time in seconds
     * @var int
     */
    private $cacheDuration = 3600;

    public function __construct($driver, $hostname, $port, $username, $password, $database) {
        try {
            $this->pdo = new PDO($driver . ':host=' . $hostname . ';port=' . $port . ';dbname=' . $database, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @see IDatabaseProvider
     */
    public function executeSelect() {
        if ($this->statement === NULL) {
            throw new DatabaseException('Query sql of Database must be set in order to execute');
        }
        $cacheKey = $this->statement->queryString;
        if ($this->isCached($cacheKey)) {
            $this->queryResult = $this->cache[$this->statement->queryString];
            return $this;
        }
        $this->statement->execute($this->queryData);
        if ($this->fetchClass !== '') {
            $this->queryResult = $this->statement->fetchAll(PDO::FETCH_CLASS, $this->fetchClass);
            //reset fetch
            $this->fetchClass = '';
        } else {
            $this->queryResult = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        }
        $this->cache[$cacheKey] = $this->queryResult;

        return $this;
    }

    /**
     * @see IDatabaseProvider
     */
    public function executeInsert() {
        if ($this->statement === NULL) {
            throw new DatabaseException('Query sql of Database must be set in order to execute');
        }
        $this->queryResult = $this->statement->execute($this->queryData);

        return $this;
    }

    /**
     * @see IDatabaseProvider
     */
    public function executeUpdate() {
        if ($this->statement === NULL) {
            throw new DatabaseException('Query sql of Database must be set in order to execute');
        }

        $this->queryResult = $this->statement->execute($this->queryData);

        return $this;
    }

    /**
     * @see IDatabaseProvider
     */
    public function executeDelete() {
        if ($this->statement === NULL) {
            throw new DatabaseException('Query sql of Database must be set in order to execute');
        }

        $this->queryResult = $this->statement->execute($this->queryData);

        return $this;
    }

    /**
     * @see IDatabaseProvider
     */
    public function setQueryData($data) {
        if (!is_array($data)) {
            throw new DatabaseException('Query $data at setQueryData of Database must array.');
        }
        $this->queryData = $data;

        return $this;
    }

    /**
     * @see IDatabaseProvider
     */
    public function setQuerySql($sql) {
        if (!is_string($sql)) {
            throw new DatabaseException('Query $sql at setQuerySql of Database must string.');
        }
        $this->statement = $this->pdo->prepare($sql);
        $this->cacheStartTime = time();

        return $this;
    }

    /**
     * 
     */
    public function setResultClass($className) {
        if (!is_string($className)) {
            throw new DatabaseException('Set Result Class of DatabaseProvider must be of type string');
        }
        $this->fetchClass = $className;

        return $this;
    }

    /**
     * @see IDatabaseProvider
     */
    public function getResult() {
        return $this->queryResult;
    }

    /**
     * valid Cache checks if the cache contains the specified key and if the cache time 
     *  has passed. If both the criteria are satisfied then it returns true, otherwise 
     *  returns false
     * 
     * @param string $cacheKey
     * @return boolean
     */
    private function isCached($cacheKey) {
        if (!isset($this->cache[$cacheKey])) {
            return false;
        }
        if ($this->cacheStartTime + $this->cacheDuration <= time()) {
            $this->cache[$cacheKey] = [];
            return false;
        }
        return true;
    }

    /**
     * 
     * @see IDatabaseProvider
     */
    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }

}
