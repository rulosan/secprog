<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 16:47
 */

namespace src\controllers;

use Slim\Slim;
use src\dao\Compras;

class XSSController
{

    public function salvar_compra()
    {
        $slim = Slim::getInstance();
        if($slim->request->isGet())
        {
            $slim->render('salvar_compra.php', [
                "url"=> "/xss/vuln",
                "compras" => Compras::buscar_todas(),
            ]);
        }
        else if($slim->request->isPost())
        {
            $nombre = $slim->request->params('nombre');
            $precio = $slim->request->params('precio');

            if(!is_string($nombre))
                die("El nombre no es cadena");
            if(!is_numeric($precio))
                // xss reflejado
                die("El precio no es numero y contiene ".$precio);

            try
            {
                // xss guardado
                $id = Compras::guardar_compra($nombre, $precio);
                die("Se guardo correctamente ".$id);
            }
            catch(\PDOException $ex)
            {
                die($ex->getMessage());
            }
        }
    }
}