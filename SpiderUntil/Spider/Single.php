<?php

namespace WebSpiderUntil\SpiderUntil\Spider;
require_once __DIR__ . '/Base.php';

class Single extends Base
{
    protected static array $req_config;

    /**
     * @param $req_config
     */
    public function __construct($req_config)
    {
        self::$req_config = $req_config;
    }


    /**
     * @return bool|string
     */
    public static function single_scrape()
    {
        $curl = curl_init();

        curl_setopt_array($curl, self::$req_config);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}


/**
 * @example
 */
$req_config = [
    CURLOPT_URL => 'http://www.baidu.com',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => 'gzip',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 3,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_POSTFIELDS => [],
    CURLOPT_HTTPHEADER => [],
];
$single = new Single($req_config);
for ($_ = 0; $_ <= 10 << 10; $_++) {
    $single->single_scrape();
    var_dump($_);
}



