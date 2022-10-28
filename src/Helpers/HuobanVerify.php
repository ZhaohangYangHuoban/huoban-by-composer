<?php
/*
 * @Author: ZhaohangYang <yangzhaohang@comsenz-service.com>
 * @Date: 2021-05-25 10:26:41
 * @Description: 伙伴智慧大客户研发部
 */

namespace Huoban\Helpers;

class HuobanVerify
{
    public static $logPath;

    public static function init($storage_path)
    {
        self::$logPath = $storage_path . DIRECTORY_SEPARATOR . 'huoban' . DIRECTORY_SEPARATOR . 'logs';
        is_dir(self::$logPath) || mkdir(self::$logPath, 0777, true);
    }

    /**
     * 效验伙伴请求返回结果
     *
     * @param [type] $response
     * @param string $location
     * @return void
     */
    public static function verifyHuobanResponse($response, $location = '', $type = 'throw', $supplementary = '')
    {
        if (isset($response['code'])) {
            $message = $response['message'] ?? '未知错误信息';
            $message .= PHP_EOL . $supplementary;

            if ('log' == $type) {
                self::error($location . PHP_EOL . $message);
            } else {
                throw new \Exception($location . $message, 10001);
            }
        }
    }

    /**
     * 打印错误日志
     *
     * @param [type] $message
     * @return void
     */
    public static function error($message)
    {
        self::log($message, 'ERROR');
    }

    /**
     * 打印信息日志
     *
     * @param [type] $message
     * @return void
     */
    public static function info($message)
    {
        self::log($message, 'INFO');
    }

    /**
     * 打印日志
     *
     * @param [type] $message
     * @param [type] $type
     * @param string $log_name
     * @return void
     */
    public static function log($message, $type = null, $log_name = '')
    {
        $date     = date('Y-m-d', time());
        $log_file = self::$logPath . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . $log_name . '.log';

        is_file($log_file) || touch($log_file, 0777, true);

        $date_time = date('Y-m-d H:i:s', time());
        $message   = '[' . $date_time . ' ' . $type . ':]' . $message . PHP_EOL;

        file_put_contents($log_file, $message, FILE_APPEND);
    }

    /**
     * 收集错误信息到伙伴
     *
     * @param [type] $item_id
     * @param [type] $field_key
     * @param [type] $field_value
     * @return void
     */
    public static function collectError($huoban_item, $item_id, $field_key, $field_value)
    {
        if ($item_id) {
            $body = [
                'fields' => [
                    $field_key => $field_value,
                ],
            ];
            $huoban_item->update($item_id, $body);
        }
    }
}
