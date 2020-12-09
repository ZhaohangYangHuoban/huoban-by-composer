<?php

namespace Huoban\Models;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use Huoban\Huoban;

class HuobanFile
{
    public static function upload($file_name, $file_path, $type = 'attachment')
    {
        Huoban::switchFile();

        $res = Huoban::$client->request('POST', '/v2/file', [
            'multipart' => [
                [
                    'contents' => fopen($file_path . '/' . $file_name, 'r'),
                    'name'      =>  'source',
                ],
                [
                    'name'      =>  'type',
                    'contents'      => 'attachment',
                ],
                [
                    'name'      =>  'name',
                    'contents'      => $file_name,
                ]
            ],
        ]);
        if ($res->getStatusCode() != 200) exit("Something happened, could not retrieve data");
        $response = json_decode($res->getBody(), true);

        Huoban::switchApi();

        return $response;
    }
}
