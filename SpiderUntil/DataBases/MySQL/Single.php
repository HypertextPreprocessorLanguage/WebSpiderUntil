<?php
require_once __DIR__ . '/Base.php';

/**
 * MySQL Single extends Base Class
 */
class Single extends Base
{
    public function __construct($config)
    {
//
//        $host,$port, $user, $password, $dbname, $client
        $host = empty($config['host']) ? '127.0.0.1' : $config['host'];
        $port = empty($config['port']) ? 3306 : $config['port'];
        $user = empty($config['user']) ? 'user' : $config['user'];
        $password = empty($config['password']) ? 'password' : $config['password'];
        $dbname = empty($config['dbname']) ? 'dbname' : $config['dbname'];
        $client = empty($config['client']) ? 'client' : $config['client'];
        parent::__construct($host, $port, $user, $password, $dbname, $client);
    }
}

$config = [
    'host' => '10.21.200.48',
    'port' => 3306,
    'user' => 'opinion',
    'password' => 'vDGM0lspmy=',
    'dbname' => 'opinion',
    'client' => 'mysql',
];
$single = new Single($config);
$results = $single->fetchAll("show tables");
foreach ($results as $result) {
    var_dump($result);
}
// $dsn = 'mysql:dbname=opinion;host=10.21.200.48;port=3306;';
//$user = 'opinion';
//$password = 'vDGM0lspmy=';



