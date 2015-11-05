<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 14:54
 */

namespace src\libs;


class Logger
{

    public static function log_sql_query($name)
    {
        return function($query, $query_time) use ($name)
        {

        };
    }


    public static function log_pdo_exception($message)
    {
        error_log($message);
    }
}