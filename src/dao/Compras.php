<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 16:12
 */
namespace src\dao;

use PDOException;
use ORM;
use src\libs\Logger;
use PDO;

class Compras
{
    public static function vulnerable_buscar_por_id($id)
    {
        try
        {
            $pdo = ORM::get_db("sp");
            $query = "SELECT * FROM compras WHERE id = ".$id;

            $rows = $pdo->query($query, PDO::FETCH_ASSOC);
            return $rows->fetchAll();
        }
        catch(PDOException $ex)
        {
            Logger::log_pdo_exception($ex->getMessage());
            throw $ex;
        }
    }

    public static function secure_buscar_por_id($id)
    {
        try
        {
            $pdo = ORM::get_db("sp");
            $stmt = $pdo->prepare("SELECT * FROM compras WHERE id = :id");
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        }
        catch(PDOException $ex)
        {
            Logger::log_pdo_exception($ex->getMessage());
            throw $ex;
        }
    }

    public static function guardar_compra($nombre, $precio)
    {
        try
        {
            ORM::for_table('compras','sp')
                ->create([
                    "nombre" => $nombre,
                    "precio"=>$precio
                ])
                ->save();
            return ORM::get_db('sp')->lastInsertId();

        }
        catch(PDOException $ex)
        {
            Logger::log_pdo_exception($ex->getMessage());
            throw $ex;
        }
    }

    public static function buscar_todas()
    {
        try
        {
            return ORM::for_table('compras','sp')->find_array();
        }
        catch(PDOException $ex)
        {
            Logger::log_pdo_exception($ex->getMessage());
            throw $ex;
        }
    }
}