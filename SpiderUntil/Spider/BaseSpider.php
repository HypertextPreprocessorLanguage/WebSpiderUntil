<?php

namespace WebSpiderUntil\SpiderUntil\Spider;

/**
 * Spider Base Class
 */
class BaseSpider
{
    /**
     * @param string $method
     * @param string $url
     * @param array $header
     * @param array $data
     * @return bool|string
     */
    public static function singleScrape(string $method, string $url, array $header, array $data)
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
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CONNECTTIMEOUT => 3,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * @param $request_config
     * @return bool|string
     */
    public static function singleConfigurableScrape($request_config)
    {
        $curl = curl_init();

        curl_setopt_array($curl, $request_config);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }


    /**
     * @param string $method
     * @param array $task
     * @param array $header
     * @param array $data
     * @return array
     */
    public static function multiScrape(string $method, array $task, array $header, array $data): array
    {
        if (false == is_array($task)) {
            return [];
        }

        $ch = curl_init();
        $mh = curl_multi_init();

        $channel = [];

        foreach ($task as $key => $info) {
            if (is_array($info) === false || isset($info['url']) === false) {
                continue;
            }
            $url = $info['url'];

            curl_setopt($ch, CURLOPT_URL, $url) && curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'gzip',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_CONNECTTIMEOUT => 3,
            ]);

            $data = $info['data'] ?? null;
            if (false == empty($data)) {
                curl_setopt($ch, CURLOPT_POST, 1);
                if (is_array($data) && count($data) > 0) {
                    curl_setopt($ch, CURLOPT_POST, count($data));
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            $channel[$key] = $ch;
            curl_multi_add_handle($mh, $channel[$key]);
        }


        $running = NULL;
        do {
            usleep(rand(0, 10) ** 5);
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $response = array();
        foreach ($task as $key => $info) {
            $response[$key] = curl_multi_getcontent($channel[$key]);
            curl_multi_remove_handle($mh, $channel[$key]);
        }

        curl_multi_close($mh);
        curl_close($ch);

        return $response;
    }

    /**
     * @param $task
     * @param $req_config
     * @return array
     */
    public static function multiConfigurableScrape($task, $req_config): array
    {
        if (false == is_array($task)) {
            return [];
        }

        $ch = curl_init();
        $mh = curl_multi_init();

        $channel = [];

        foreach ($task as $key => $info) {
            if (is_array($info) === false || isset($info['url']) === false) {
                continue;
            }
            $url = $info['url'];

            curl_setopt($ch, CURLOPT_URL, $url) && curl_setopt_array($ch, $req_config);

            $data = $info['data'] ?? null;
            if (false == empty($data)) {
                curl_setopt($ch, CURLOPT_POST, 1);
                if (is_array($data) && count($data) > 0) {
                    curl_setopt($ch, CURLOPT_POST, count($data));
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            $channel[$key] = $ch;
            curl_multi_add_handle($mh, $channel[$key]);
        }


        $running = NULL;
        do {
            usleep(rand(0, 10) ** 5);
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $response = array();
        foreach ($task as $key => $info) {
            $response[$key] = curl_multi_getcontent($channel[$key]);
            curl_multi_remove_handle($mh, $channel[$key]);
        }

        curl_multi_close($mh);
        curl_close($ch);

        return $response;
    }

}