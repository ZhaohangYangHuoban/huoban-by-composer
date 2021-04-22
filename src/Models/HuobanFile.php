<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanFile
{
    /**
     * 上传文件
     *
     * @param array $body
     * @param array $options
     * @return void
     */
    public static function upload($body = [], $options = [])
    {
        //  example

        //  $body = [
        //      'multipart' => [
        //          [
        //              'contents' => fopen($file_path . '/' . $file_name, 'r'),
        //              'name'     => 'source',
        //          ],
        //          [
        //              'name'     => 'type',
        //              'contents' => 'attachment',
        //          ],
        //          [
        //              'name'     => 'name',
        //              'contents' => $file_name,
        //          ],
        //      ],
        //  ];

        Huoban::setHttpClient('upload');
        $response = Huoban::getHttpClient()->request('POST', "/file", $body, $options);
        Huoban::setHttpClient('api');
        return json_decode($response->getBody(), true);
    }
}
