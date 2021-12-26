<?php

namespace WebSpiderUntil\SpiderUntil\Spider;
/**
 * Spider Base class
 */
class Base
{
    /**
     * scrape: Simple encapsulation of request method
     * @param string $method
     * @param string $url
     * @param array $header
     * @param array $data
     * @return bool|string
     */
    public static function scrape(string $method, string $url, array $header, array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
