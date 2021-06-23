<?php

namespace Huoban\Models\Package;

/**
 * 二次封装伙伴基础模型
 */
trait Item
{
    /**
     * 根据item_id，返回对应数据
     *
     * @param [type] $item_id
     * @return array
     */
    public function getItem($item_id)
    {
        $item = parent::get($item_id);
        if (isset($item['code'])) {
            throw new \Exception("根据item_id/{$item_id}，获取数据失败：" . $item['message'], 100001);
        }
        return $item;
    }
    /**
     * 根据item_id，返回对应的格式化数据
     *
     * @param [type] $item_id
     * @return array
     */
    public function getFormatItem($item_id)
    {
        $item = $this->getItem($item_id);
        return parent::returnDiy($item);
    }
    /**
     * 更新数据
     *
     * @param [type] $item_id
     * @param [type] $body
     * @return void
     */
    public function updateItem($item_id, $body)
    {
        return parent::update($item_id, $body);
    }
    /**
     * 更新数据并返回格式化的数据信息
     *
     * @param [type] $item_id
     * @param [type] $body
     * @return void
     */
    public function updateFormatItem($item_id, $body)
    {
        $item = $this->updateItem($item_id, $body);
        return parent::returnDiy($item);
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
