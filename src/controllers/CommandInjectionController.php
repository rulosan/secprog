<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 17:13
 */

namespace src\controllers;


use Slim\Slim;

class CommandInjectionController
{
    public static function ping()
    {
        $slim = Slim::getInstance();

        if($slim->request->isGet())
        {
            $cmd = $slim->request->params('cmd');
            $output = shell_exec("ping -c 4 ".$cmd);
            //$output = shell_exec("ping -c 4".escapeshellcmd($cmd));
            die($output);
        }
    }

}