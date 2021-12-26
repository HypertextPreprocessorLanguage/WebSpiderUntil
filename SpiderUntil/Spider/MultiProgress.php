<?php

namespace WebSpiderUntil\SpiderUntil\Spider;
require_once __DIR__ . '/Base.php';

class MultiProgress
{

    public static function multiScrape($nodes, $header): array
    {
        if (false == is_array($nodes)) {
            return array();
        }

        $ch = curl_init();
        $mh = curl_multi_init();

        $curlArray = array();

        foreach ($nodes as $key => $info) {
            if (is_array($info) === false || isset($info['url']) === false) {
                continue;
            }
            $url = $info['url'];

            curl_setopt($ch, CURLOPT_URL, $url) && curl_setopt_array($ch, $header);

            $data = $info['data'] ?? null;
            if (false == empty($data)) {
                curl_setopt($ch, CURLOPT_POST, 1);
                // array
                if (is_array($data) && count($data) > 0) {
                    curl_setopt($ch, CURLOPT_POST, count($data));
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            $curlArray[$key] = $ch;
            curl_multi_add_handle($mh, $curlArray[$key]);
        }

        $running = NULL;

        do {
            usleep(rand(0, 10) ** 5);
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $res = array();
        foreach ($nodes as $key => $info) {
            $res[$key] = curl_multi_getcontent($curlArray[$key]);
        }

        foreach ($nodes as $key => $info) {
            curl_multi_remove_handle($mh, $curlArray[$key]);
        }

        curl_multi_close($mh);

        return $res;
    }

}

$count = 10 << 10;
$url = 'https://www.cnblogs.com/rxbook/p/10764080.html';
$nodes = array();
for ($_ = 1; $_ <= $count; $_++) {
    $nodes[] = [
        'url' => $url,
    ];
}
$header = [
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:22.0) Gecko/20100101 Firefox/22.0',
    CURLOPT_CONNECTTIMEOUT => 3,
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
$startTime = microtime(true);
$res = MultiProgress::multiScrape($nodes, $header);
var_dump($res);
$endTime = microtime(true);
echo $endTime - $startTime . PHP_EOL;
