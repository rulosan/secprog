<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 15:02
 */

namespace src\libs;


class Authorization
{
    public static function hook($profile)
    {
        return function () use ($profile)
        {
            return true;
        };
    }
}