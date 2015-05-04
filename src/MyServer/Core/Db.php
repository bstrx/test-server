<?php

namespace MyServer\Core;

use PDO;
use PDOException;
use PDOStatement;

class Db
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var string
     */
    private $dbUser;

    /**
     * @var string
     */
    private $dbPassword;

    /**
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     */
    public function __construct($dbName, $dbUser, $dbPassword)
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
    }

    /**
     * @param $sql
     * @param array $options
     * @return PDOStatement
     */
    public function execute($sql, $options = [])
    {
        $preparedStatement = $this->getConnection()->prepare($sql);
        if ($preparedStatement === false) {
            throw new PDOException();
        }
        $preparedStatement->execute($options);

        return $preparedStatement;
    }

    public function fetchAll($tableName, $orderBy = [])
    {
        /** @var PDOStatement $statement */
        $statement = $this->getStatement($tableName, [], null, $orderBy);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function fetchBy($tableName, $fetchOptions, $limit = null, $orderBy = [])
    {
        /** @var PDOStatement $statement */
        $statement = $this->getStatement($tableName, $fetchOptions, $limit, $orderBy);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function fetchOneBy($tableName, $fetchOptions)
    {
        /** @var PDOStatement $statement */
        $statement = $this->getStatement($tableName, $fetchOptions, 1);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * @param string $tableName
     * @param array $fetchOptions
     * @param integer $limit
     * @return array
     */
    public function getStatement($tableName, $fetchOptions = [], $limit = null, $orderBy = [])
    {
        $whereCondition = '';
        $optionValues = [];
        if ($fetchOptions) {
            list($optionKeys, $optionValues) = $this->prepareKeyValues($fetchOptions);
            $whereCondition = ' WHERE ' . implode(' AND ', $optionKeys);
        }

        $orderByCondition = '';
        $orderByValues = [];
        if ($orderBy) {
            list($orderByKeys, $orderByValues) = $this->prepareKeyValues($orderBy);
            $orderByCondition = ' ORDER BY ' . implode(', ', $orderByKeys);
        }

        $limitCondition = '';
        if ($limit) {
            $limitCondition = ' LIMIT ' . (int) $limit;
        }

        $sql = "SELECT * FROM $tableName $whereCondition $orderByCondition $limitCondition";

        print_r(array_merge($optionValues, $orderByValues));
        return $this->execute($sql, array_merge($optionValues, $orderByValues));
    }

    /**
     * @param string $tableName
     * @param array $deleteOptions
     */
    public function delete($tableName, $deleteOptions = [])
    {
        $sql = "DELETE FROM $tableName";
        $optionValues = [];

        if ($deleteOptions) {
            list($optionKeys, $optionValues) = $this->prepareKeyValues($deleteOptions);
            $sql .= " WHERE " . implode(' AND ', $optionKeys);
        }

        $this->execute($sql)->execute($optionValues);
    }

    /**
     * @param string $tableName
     * @param array $insertOptions
     * @return array
     */
    public function insert($tableName, $insertOptions)
    {
        $optionKeys = array_keys($insertOptions);
        $optionValues = array_values($insertOptions);
        $valuesCount = count($optionValues);
        $bindArray = [];
        for($i = 0; $i < $valuesCount; $i++ ) {
            $bindArray[] = '?';
        }
        $keys = implode(', ', $optionKeys);
        $values = implode(', ', $bindArray);
        $sql = "INSERT INTO $tableName ($keys) VALUES($values) ";

        return $this->execute($sql)->execute($optionValues);
    }

    /**
     * @param string $tableName
     * @param array $newValues
     * @param array $conditions
     */
    public function update($tableName, array $newValues, array $conditions = [])
    {
        list($updateKeys, $updateValues) = $this->prepareKeyValues($newValues);
        $values = implode(', ', $updateKeys);
        $sql = "UPDATE $tableName SET $values";
        $conditionValues = [];

        if ($conditions) {
            list($conditionKeys, $conditionValues) = $this->prepareKeyValues($conditions);
            $condition = implode(' AND ', $conditionKeys);
            $sql .= " WHERE $condition";
        }
print_r($sql);
print_r(array_merge($updateValues, $conditionValues));
        $this->execute($sql, array_merge($updateValues, $conditionValues));
    }

    /**
     * @param $tableName
     * @param array $newValues
     * @param array $conditions
     */
    public function insertOrUpdate($tableName, array $newValues, array $conditions = [])
    {
        //TODO
    }

    /**
     * @return PDO
     */
    private function getConnection()
    {
        if (!$this->connection) {
            $this->connection = new PDO("mysql:host=localhost; dbname=$this->dbName", $this->dbUser, $this->dbPassword);
        }

        return $this->connection;
    }

    /**
     * @param array $keyValues
     * @return array
     */
    private function prepareKeyValues(array $keyValues)
    {
        $updateKeys = array_keys($keyValues);
        $updateValues = array_values($keyValues);
        foreach ($updateKeys as &$value) {
            $value .= ' = ?';
        }

        return [$updateKeys, $updateValues];
    }
}
