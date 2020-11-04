<?php
require_once __DIR__ . "/vendor/autoload.php";
// 根目录执行
use Huoban\Huoban;
use Huoban\Models\HuobanItem;

defined('TEST') or define('TEST', false);

$app_type = 'e';
$application_id = '';
$application_secret = '';

$space_id = '';

$huoban = Huoban::init([
    'app_type' => 'enterprise',
    'application_id' => '1000307',
    'application_secret' => 'GkCtOwFXsr1Sqsne6TNi0gMmwHZxKqTn9AzLyuEw',
    'alias_model' => true,
    'space_id' => '4000000002101383',
]);

$huoban_data = HuobanItem::find("T::ceshi1");


$huoban = Huoban::switchTmpAuth([
    'app_type' => 'enterprise',
    'application_id' => '1000307',
    'application_secret' => 'GkCtOwFXsr1Sqsne6TNi0gMmwHZxKqTn9AzLyuEw',
    'alias_model' => true,
    'space_id' => '4000000002765282',
]);


$huoban_data = HuobanItem::find("T::app_test");
print_r($huoban_data);

// // $huoban->aliasModel($space_id);

// // $huoban_data = $huoban->getHuobanItem()->findAllExc("T::test");


// $huoban_data = '';
// function a($a => $b)
// {
//     echo 'key=>' . $a . PHP_EOL;

//     echo 'key=>' . $b . PHP_EOL;

//     // foreach ($params as $key => $value) {
//     //     echo 'key=>' . $key . '   value=>' . $value . PHP_EOL;
//     // }
// }
// $a = 'aaa';

// a(...['aaaa', 'bbbb']);
// print_r($huoban_data);
