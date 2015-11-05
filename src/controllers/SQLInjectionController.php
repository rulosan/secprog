<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 16:04
 */

namespace src\controllers;


use Slim\Slim;
use src\dao\Compras;
use Exception;

class SQLInjectionController
{

    /*
     * 1 -> 1%2B1
     * 2 -> 1%20UNION%20SELECT%201
     * 3 -> 1%20UNION%20SELECT%201%2
     * 4 -> 1%20UNION%20SELECT%201%2C2%2C3
     */
    public static function vulnerable()
    {
        $slim = Slim::getInstance();

        if($slim->request->isGet())
        {
            $id = $slim->request->params("id");
            try
            {
                $compras = Compras::vulnerable_buscar_por_id($id);
                die(json_encode($compras));
            }
            catch(Exception $ex)
            {
                die($ex->getMessage());
            }
        }
    }

    public static function secure()
    {
        $slim = Slim::getInstance();

        if($slim->request->isGet())
        {
            $id = $slim->request->params("id");
            if(!is_int($id))
                die("El parametro no es un numero");
            try
            {
                die(json_encode(Compras::secure_buscar_por_id($id)));
            }
            catch(Exception $ex)
            {
                die($ex->getMessage());
            }
        }
    }

}