<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 17:22
 */

namespace src\controllers;


use Slim\Slim;
use src\libs\SafeUploader;

class OtherVulnerabilitiesController
{

    // /rfi?file=../../../../../../etc/hosts
    public static function show_lfi()
    {
        $slim = Slim::getInstance();

        if($slim->request->isGet())
        {
            $file = $slim->request->params('file');
            echo include("./src/templates/".$file);
        }
    }


    public static function show_rfi()
    {
        $slim = Slim::getInstance();

        if($slim->request->isGet())
        {
            $slim->render('upload_file.php', ["url" => "/rfi"]);

        }
        else if($slim->request->isPost())
        {
            if($_FILES['photo']['name'])
            {
                if(!$_FILES['photo']['error'])
                {
                    $new_file_name = strtolower($_FILES['photo']['tmp_name']); //rename file
                    if($_FILES['photo']['size'] > (1024000)) //can't be larger than 1 MB
                    {
                        die('Oops!  Your file\'s size is to large.');
                    }
                    else
                    {
                        move_uploaded_file($_FILES['photo']['tmp_name'], './uploads/'.$_FILES["photo"]["name"]);
                        die('Congratulations!  Your file was accepted.');
                    }
                }
                else
                {
                    die('Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error']);
                }
            }
        }
    }

    public static function show_sec_upload()
    {
        $slim = Slim::getInstance();
        if($slim->request->isGet())
        {
            $slim->render('upload_file.php',["url" => "/sec/rfi"]);
        }
        else if($slim->request->isPost())
        {
            $filename = SafeUploader::uploading();
            if(is_string($filename))
                die("se subio correctamente http://secureprogramming.dev/uploads/".$filename);
            die("problemas en el upload del archivo");
        }
    }
}