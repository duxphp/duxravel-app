<?php

namespace Duxravel\Core\Util;

use Duxravel\Core\Exceptions\ErrorException;
use Duxravel\Core\UI\Widget\Icon;

/**
 * 高德位置服务
 */
class WebService
{
    private $key;

    /**
     * @param string $key
     */
    public function __construct(string $key = '')
    {
        if ($key) {
            $this->key = $key;
        } else {
            $this->key = config('dux.map_amap_key');
        }
    }

    /**
     * @param $url
     * @param $data
     * @return mixed
     * @throws ErrorException
     */
    private function request($url, $data)
    {
        $client = new \GuzzleHttp\Client();
        $params = array_merge([
            'key' => $this->key,
        ], $data);
        $url = trim($url, '&') . '&' . http_build_query($params);

        $data = \GuzzleHttp\json_decode($client->request('get', $url)->getBody()->getContents(), true);

        if (!$data || !$data['status']) {
            app_error($data['info'] ?: '无法获取服务');
        }
        return $data;
    }

    /**
     * @return mixed|null
     */
    public function getIp(): ?string
    {
        $client = new \GuzzleHttp\Client();
        $text = $client->get('https://ip.tool.lu/')->getBody()->getContents();
        preg_match('/(([01]{0,1}\d{0,1}\d|2[0-4]\d|25[0-5])\.){3}([01]{0,1}\d{0,1}\d|2[0-4]\d|25[0-5])/', $text, $arr);
        return $arr[0] ?: null;
    }

    /**
     * @param null $ip
     * @return array
     * @throws ErrorException
     */
    public function getArea($ip = null): array
    {
        $data = $this->request('https://restapi.amap.com/v5/ip?parameters', [
            'type' => 4,
            'ip' => $ip ?: $this->getIp()
        ]);
        return [
            'country' => $data['country'],
            'province' => $data['province'],
            'city' => $data['city'],
            'district' => $data['district'],
            'isp' => $data['isp'],
            'location' => $data['location'],
            'ip' => $data['ip'],
        ];
    }

    /**
     * @param null $city
     * @return array|mixed
     * @throws ErrorException
     */
    public function getWeather($city = null)
    {
        $ipData = [];
        if (!$city) {
            $ipData = $this->getArea();
        }
        if (!$city && !$ipData['city']) {
            app_error('暂未找到城市');
        }
        $data = $this->request('https://restapi.amap.com/v3/weather/weatherInfo?parameters', [
            'city' => $city ?: $ipData['city'],
        ]);
        return $data['lives'][0] ?: [];
    }

}
