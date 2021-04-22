<?php

namespace Huoban\Models;

use Huoban\Huoban;

class HuobanBiFile
{
    /**
     * 上传数据仓库数据文件（用于创建数据仓库表数据）
     *
     * @param array $body
     * @param array $options
     * @return void
     */
    public static function upload($body = [], $options = [])
    {
        //  example

        //   $body = [
        //        'multipart' => [
        //            [
        //                'contents' => fopen('/storage/test.data', 'r'), // test.data:每一行是一个json,{字段别名:字段值....},\n结尾
        //                'name'     => 'source',
        //            ],
        //            [
        //                'name'     => 'type',
        //                'contents' => 'create / update',
        //            ],
        //            [
        //                'name'     => 'table_alias',
        //                'contents' => $table_alias,
        //            ],
        //            [
        //                'name'     => 'space_id',
        //                'contents' => $space_id,
        //            ],
        //        ],
        //  ];

        Huoban::setHttpClient('bi');
        try {
            $response = Huoban::getHttpClient()->request('POST', '/v2/app_sync/file', $body, $options);
            $response = json_decode($response->getBody(), true);
        } catch (\Throwable $th) {
            $response = $th->getMessage();
        }
        Huoban::setHttpClient('api');
        return $response;

    }
}
