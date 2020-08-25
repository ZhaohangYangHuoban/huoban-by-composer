<?php
require_once __DIR__ . "/vendor/autoload.php";
// 根目录执行
use Huoban\Huoban;
use Huoban\Models\HuobanItem;

defined('TEST') or define('TEST', false);

$app_type = '';
$application_id = '';
$application_secret = '';

$space_id = '';

Huoban::init($app_type, $application_id, $application_secret);
Huoban::aliasModel($space_id);

$huoban_data = HuobanItem::findAllExc("T::test");