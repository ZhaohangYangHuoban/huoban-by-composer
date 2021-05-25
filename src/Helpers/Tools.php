<?php

namespace Huoban\Helpers;

use Huoban\Huoban;

class Tools
{
    // 提取某一列为键名生成数组
    public static function extractCloumnForArray(String $col_name, array $array): array
    {
        return array_combine(array_column($array, $col_name), $array);
    }

    public static function getConfig(array $config): array
    {
        return $config + [
            'alias_model' => true,
            'app_type'    => 'enterprise',
            'api_url'     => 'https://api.nb-health.com',
            'upload_url'  => 'https://upload.huoban.com',
        ];
    }

    public static function getTablesForSpaceId(Huoban $huoban_obj, ?int $expired): array
    {
        $expired = $expired ?: 1209600;

        $space = $huoban_obj->_cache->remember($huoban_obj->config['name'] . 'space', $expired, function () use ($huoban_obj) {
            return $huoban_obj->_space->getSpace($huoban_obj->config['space_id']);
        });
        foreach ($space['table_ids'] as $table_id) {
            $tables[] = $huoban_obj->_cache->remember($huoban_obj->config['name'] . $table_id, $expired, function () use ($huoban_obj, $table_id) {
                return $huoban_obj->_table->get($table_id);
            });
        }

        return $tables;
    }

    public static function syncAlias(Huoban $huoban_source_obj, Huoban $huoban_target_obj, ?int $expired): array
    {

        $source_tables = Tools::getTablesForSpaceId($huoban_source_obj, null);
        $target_tables = Tools::getTablesForSpaceId($huoban_target_obj, null);

        $source_tables = self::extractCloumnForArray('name', $source_tables);
        $target_tables = self::extractCloumnForArray('name', $target_tables);

        $responses   = [];
        $table_names = array_column($source_tables, 'name');

        foreach ($table_names as $table_name) {
            if (isset($source_tables[$table_name]) && isset($target_tables[$table_name])) {
                $table_body  = self::getTargetAliasTable($source_tables[$table_name], $target_tables[$table_name]);
                $responses[] = $huoban_target_obj->_table->setAlias($target_tables[$table_name]['table_id'], $table_body);
            }
        }

        return $responses;
    }

    public static function getTargetAliasTable(array $source_table, array $target_table): array
    {

        $source_fields = self::extractCloumnForArray('name', $source_table['fields']);
        $target_fields = self::extractCloumnForArray('name', $target_table['fields']);

        $field_names = array_column($source_fields, 'name');

        $field_body = [];
        foreach ($field_names as $field_name) {
            if (isset($source_fields[$field_name]) && isset($target_fields[$field_name])) {
                self::getTargetAliasField($source_fields[$field_name], $target_fields[$field_name], $field_body);
            }
        }
        return ['alias' => $source_table['alias'] ?? '', 'fields' => $field_body, 'install_style' => 'old'];

    }

    public static function getTargetAliasField(array $source_field, array $target_field, array &$field_body): void
    {

        $source_field_alias = $source_field['alias'] ?? '';
        $source_field_alias = explode('.', $source_field_alias);
        $source_field_alias = $source_field_alias[1] ?? '';

        $target_field_field_id = $target_field['field_id'];

        $field_body[$target_field_field_id] = $source_field_alias;
    }

}
//
// $application_id     = 1000001;
// $application_secret = 'ca1leod3ojCcWmk8uy62MhPKT2QKfjklaswqtnjiuyt';
//
// $zongbu_huoban   = new Huoban(Tools::getConfig(['name' => 'zongbu', 'space_id' => '4000000002766964', 'application_id' => $application_id, 'application_secret' => $application_secret, 'api_url' => 'https://api.nb-health.com']));
// $mingzhou_huoban = new Huoban(Tools::getConfig(['name' => 'mingzhou', 'space_id' => '4000000008166419', 'application_id' => $application_id, 'application_secret' => $application_secret, 'api_url' => 'https://api.nb-health.com']));
//
// $responses = Tools::syncAlias($zongbu_huoban, $mingzhou_huoban, null);
//
// print_r($responses);exit;
