<?php
/*
 * @Author: SanQian
 * @Date: 2021-09-08 16:22:45
 * @LastEditTime: 2021-09-08 16:35:40
 * @LastEditors: SanQian
 * @Description:
 * @FilePath: /kuaidi100/vendor/zhaohangyang/huoban_tools_php/src/Models/Package/Table.php
 *
 */

namespace Huoban\Models\Package;

use Huoban\Tools\ToolsField;

/**
 * 二次封装伙伴基础模型
 */
trait Table
{
    // 获取要创建的表结构
    public function createTableStructure($space_id, $table_name, $table_alias, $table_alias_maintain, $maintain_fields_items)
    {

        foreach ($maintain_fields_items as $fields_item) {

            $type_id = array_shift($fields_item[$table_alias_maintain . '.app_type_ids']);

            $name  = $fields_item[$table_alias_maintain . '.app_name'];
            $alias = $fields_item[$table_alias_maintain . '.app_en_name'];

            switch ($type_id) {
                case 1: // 文本
                    $fields[] = $this->getFieldTextBasic($name, $alias);
                    break;
                case 2: // 选项
                    $category_id  = array_shift($fields_item[$table_alias_maintain . '.app_category_ids']);
                    $category_arr = json_decode($fields_item[$table_alias_maintain . '.app_category_json'], true);
                    $options      = ToolsField::getCreateFieldCategoryConfigOptions($category_arr);

                    $fields[] = $this->getFieldCategoryBasic($name, $alias, $category_id, $options);
                    break;
                case 3: // 数值
                    $fields[] = $this->getFieldNumberBasic($name, $alias);
                    break;
                case 4: // 仅日期
                    $fields[] = $this->getFieldDateBasic($name, $alias);
                    break;
                case 5: // 日期和时间
                    $fields[] = $this->getFieldDateTimeBasic($name, $alias);
                    break;
                default:
                    break;
            }
        }

        $body = $this->getTableBasic($space_id, $table_name, $table_alias, $fields);
        return $body;
    }
    // 返回创建表格的基本格式
    public function getTableBasic($space_id, $table_name, $table_alias = null, $fields = [])
    {

        $list_layout = array_column($fields, 'field_id');
        foreach ($list_layout as $field_id) {
            $field_layout[] = [$field_id];
        }

        return [
            'name'         => $table_name,
            'alias'        => $table_alias ?: md5(uniqid(microtime(true), true)),
            'icon'         => [
                'id'    => 600,
                'color' => 'a',
            ],
            'fields'       => $fields,
            'field_layout' => $field_layout,
            'list_layout'  => $list_layout,
            'space_id'     => $space_id,
            'space'        => [
                'space_id' => $space_id,
            ],
            'field_sync'   => 1,
        ];
    }
    // 返回创建数值字段的基本格式
    public function getFieldNumberBasic($name, $alias)
    {

        return [
            'field_id'        => $alias,
            'name'            => $name,
            "_name"           => $name,
            'alias'           => $alias ?: md5(uniqid(microtime(true), true)),
            "icon"            => "&#xe656;",
            "type"            => "number",
            "_new"            => true,
            "value"           => [],
            "default_setting" => [
                "type"  => "",
                "value" => "",
            ],
            "_description"    => "用于求和，求平均值等运算",
            "config"          => [
                "display_mode" => "number",
            ],
        ];
    }
    // 返回创建日期字段的基本格式
    public function getFieldDateBasic($name, $alias)
    {
        return [
            'field_id'        => $alias,
            'name'            => $name,
            'alias'           => $alias ?: md5(uniqid(microtime(true), true)),
            "type"            => "date",
            "_new"            => true,
            "value"           => [],
            "preserved"       => true,
            "icon"            => "&#xe647;",
            "id"              => "rb6huulu",
            "manual"          => true,
            "config"          => [
                "type"                   => "date",
                "show_week_day"          => false,
                "background_color_alias" => "",
            ],
            "required"        => false,
            "default_setting" => [
                "value"    => null,
                "type"     => "",
                "relation" => null,
                "script"   => null,
            ],
            "lock"            => [],
            "locked_rights"   => [],
            "description"     => "",
            "scope"           => null,
        ];
    }
    // 返回创建日期时间字段的基本格式
    public function getFieldDateTimeBasic($name, $alias)
    {
        return [
            'field_id'        => $alias,
            'name'            => $name,
            'alias'           => $alias ?: md5(uniqid(microtime(true), true)),
            "id"              => $alias,
            "_new"            => true,
            "type"            => "date",
            "icon"            => "&#xe647;",
            "preserved"       => true,
            "manual"          => true,
            "config"          => [
                "type"                   => "datetime",
                "show_week_day"          => false,
                "background_color_alias" => "",
            ],
            "required"        => false,
            "default_setting" => [
                "value"    => null,
                "type"     => "",
                "relation" => null,
                "script"   => null,
            ],
            "lock"            => [],
            "locked_rights"   => [],
            "description"     => "",
            "scope"           => null,
        ];
    }
    // 返回创建文本字段的基本格式
    public function getFieldTextBasic($name, $alias)
    {
        return [
            'field_id'        => $alias,
            'name'            => $name,
            'alias'           => $alias ?: md5(uniqid(microtime(true), true)),
            'type'            => 'text',
            '_new'            => 1,
            'value'           => [
                [
                    'value' => '',
                ],
            ],
            'preserved'       => 1,
            'icon'            => '&#xe654',
            'manual'          => '1',
            'config'          => [
                'type' => 'input',
            ],
            'default_setting' => [
                'type'  => '',
                'value' => '',
            ],
            'lock'            => [
                'update'      => 0,
                'delete'      => 0,
                'item_update' => 0,
            ],
        ];
    }
    // 返回创建选项字段的基本格式
    public function getFieldCategoryBasic($name, $alias, $category_id, $options)
    {
        return [
            "field_id"        => $alias,
            "name"            => $name,
            'alias'           => $alias,
            "id"              => $name,
            "_new"            => true,
            "options"         => null,
            "type"            => "category",
            "icon"            => "&#xe903;",
            "preserved"       => true,
            "manual"          => true,
            "config"          => [
                "display_mode"           => "list",
                "type"                   => 1 == $category_id ? "single" : "multi",
                "colorful"               => false,
                "background_color_alias" => "",
                "options"                => $options,
            ],
            "lock"            => [
                "delete"      => 0,
                "update"      => 0,
                "item_update" => 0,
            ],
            "locked_rights"   => [],
            "required"        => false,
            "description"     => "",
            "default_setting" => [
                "value" => [],
            ],
        ];
    }

}
