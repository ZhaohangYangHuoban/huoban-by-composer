<?php

namespace Huoban\Models\Package;

/**
 * 二次封装伙伴基础模型
 */
trait Items
{
    /**
     * 根据item_ids集合，返回对应数据集合
     *
     * @param [type] $table 表格：id/别名
     * @param [array] $item_ids
     * @return array
     */
    public function getItems($table, array $item_ids)
    {
        $body = [
            'where' => [
                'and' => [
                    [
                        "field" => 'item_id',
                        "query" => [
                            "in" => $item_ids,
                        ],
                    ],
                ],
            ],
        ];
        $response = parent::find($table, $body);

        if (isset($response['code'])) {
            throw new \Exception("根据item_ids/" . implode('、', $item_ids) . "，从表格/{$table}，中获取数据集合失败" . $response['message'], 100002);
        }
        return $response['items'];
    }
    /**
     * 根据item_ids集合，返回对应格式化数据集合
     *
     * @param [type] $table 表格：id/别名
     * @param [array] $item_ids
     * @return array
     */
    public function getFormatItems($table, array $item_ids)
    {
        $items = $this->getItems($table, $item_ids);
        return parent::handleItems($items);
    }

    /**
     * 根据筛选器集合，返回对应数据集合
     *
     * @param [type] $table 表格：id/别名
     * @param [array] $body
     * @return array
     */
    public function findItems($table, $body)
    {
        $response = parent::find($table, $body);

        if (isset($response['code'])) {
            throw new \Exception("根据item_ids/" . json_encode($body) . "，从表格/{$table}，中获取数据集合失败" . $response['message'], 100002);
        }
        return $response['items'];
    }
    /**
     * 根据筛选器集合，返回对应格式化数据集合
     *
     * @param [type] $table 表格：id/别名
     * @param [array] $body
     * @return array
     */
    public function findFormatItems($table, $body)
    {
        $items = $this->findItems($table, $body);
        return parent::handleItems($items);

    }

    /**
     * 上传文件 到伙伴平台，返回file_id
     *
     * @param [type] $file_data
     * @return void
     */
    public function uploadHuoban($file_data)
    {
        $body = [
            'multipart' => [
                [
                    'contents' => fopen($file_data['file_path'], 'r'),
                    'name'     => 'source',
                ],
                [
                    'name'     => 'type',
                    'contents' => 'attachment',
                ],
                [
                    'name'     => 'name',
                    'contents' => $file_data['file_name'],
                ],
            ],
        ];

        $response = parent::$_huoban->_file->upload($body);
        if (!isset($response['file_id'])) {
            throw new \Exception("上传文件失败" . $response['message'], 100003);
        }
        return $response['file_id'];
    }
}
