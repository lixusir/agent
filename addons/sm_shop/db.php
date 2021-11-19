<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\7\17 0017
 * Time: 14:41
 */
class db {

    private $connection = null;
    public function __construct( $config_db )
    {

        try {

            $this->connection = new \PDO("mysql:host=" . $config_db['hostname'] . ";port=" . $config_db['port'] . ";dbname=" . $config_db['database'],
                $config_db['username'],
                $config_db['password'],
                array(\PDO::ATTR_PERSISTENT => false));
        } catch (\PDOException $e) {
            throw new \Exception('Failed to connect to database. Reason: \'' . $e->getMessage() . '\'');
        }
        $this->connection->exec("SET NAMES 'utf8'");
        $this->connection->exec("SET CHARACTER SET utf8");
        $this->connection->exec("SET CHARACTER_SET_CONNECTION=utf8");
        $this->connection->exec("SET SQL_MODE = ''");
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function prepare($sql)
    {
        $this->statement = $this->connection->prepare($sql);
    }

    public function execute()
    {

        try {
            if ($this->statement && $this->statement->execute()) {
                $data = array();

                while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0])) ? $data[0] : array();
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch (\PDOException $e) {
            throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
        }
    }

    public function query($sql, $params = array())
    {
        $this->statement = $this->connection->prepare($sql);

        $result = false;

        try {
            if ($this->statement && $this->statement->execute($params)) {
                $data = array();

                while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0]) ? $data[0] : array());
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch (\PDOException $e) {
            throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode() . ' <br />' . $sql);
        }

        if ($result) {
            return $result;
        } else {
            $result = new \stdClass();
            $result->row = array();
            $result->rows = array();
            $result->num_rows = 0;
            return $result;
        }
    }

    public function escape($value)
    {
        return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
    }

    public function countAffected()
    {
        if ($this->statement) {
            return $this->statement->rowCount();
        } else {
            return 0;
        }
    }

    public function getLastId()
    {
        return $this->connection->lastInsertId();
    }

    public function isConnected()
    {
        if ($this->connection) {
            return true;
        } else {
            return false;
        }
    }

}
