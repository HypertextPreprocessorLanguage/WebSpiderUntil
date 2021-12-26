<?php


/**
 * MySQL Base Client
 */
class Base
{
    private PDO $pdo;

    public function __construct($host, $port, $user, $password, $dbname, $client)
    {

        $dsn = "$client:dbname=$dbname;host=$host;port=$port";
        echo "dsn: $dsn" . PHP_EOL;
        try {
            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
                PDO::ATTR_PERSISTENT => true
            ]);
        } catch (PDOException $e) {
            echo "MySQL Client Error: $e->errorInfo" . PHP_EOL;
            var_dump($e->getMessage());
        }
    }

    /**
     * @return PDO
     */
    protected function getPdo(): PDO
    {
        return $this->pdo;
    }


    /**
     * @param $sql
     * @return false|PDOStatement|void
     */
    public function query($sql)
    {
        try {
            return $this->getPdo()->query($sql);
        } catch (PDOException $e) {
            echo "Exec SQL Error: $e" . PHP_EOL;
            var_dump($sql);
        }
    }


    /**
     * @param $sql
     * @return false|int|void
     */
    public function exec($sql)
    {
        try {
            return $this->getPdo()->exec($sql);
        } catch (PDOException $e) {
            echo "Exec SQL Error: $e" . PHP_EOL;
            var_dump($sql);
        }
    }


    /**
     * @param $sql
     * @return mixed|void
     */
    public function fetch($sql)
    {
        try {
            return $this->query($sql)->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Exec SQL Error: $e" . PHP_EOL;
            var_dump($sql);
        }
    }

    /**
     * @param $sql
     * @return array|false|void
     */
    public function fetchAll($sql)
    {
        try {
            return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Exec SQL Error: $e" . PHP_EOL;
            var_dump($sql);
        }
    }

}
