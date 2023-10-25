<?php

namespace Huoban\Helpers;

use Huoban\Helpers\ToolsPackages\SyncSpaceTablesAlias;
use Huoban\Huoban;

class HuobanTools
{
    use SyncSpaceTablesAlias;

    /**
     * 提取某一列为键名生成数组
     *
     * @param String $col_name
     * @param array $array
     * @return array
     */
    public static function extractCloumnForArray(String $col_name, array $array): array
    {
        return array_combine(array_column($array, $col_name), $array);
    }
}
