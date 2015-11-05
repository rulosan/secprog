<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 21:43
 */

namespace src\libs;


class SafeUploader
{
    const FILE_INDEX    = "photo";
    const FILE_TMP_NAME = "tmp_name";
    const FILE_TYPE     = "type";
    const FILE_SIZE     = "size";
    const FILE_NAME     = "name";
    const FILE_ERROR    = "error";

    const JPEG = "jpeg";
    const GIF  = "gif";
    const PNG  = "png";

    const LIMIT_SIZE = 1024000;

    const UPLOAD_DIR = './uploads/';

    public static function uploading()
    {
        if(!isset($_FILES[self::FILE_INDEX]))
            return false;

        if($_FILES[self::FILE_INDEX][self::FILE_ERROR] != UPLOAD_ERR_OK)
            return false;

        $tmp_name = isset($_FILES[self::FILE_INDEX][self::FILE_TMP_NAME]) ? $_FILES[self::FILE_INDEX][self::FILE_TMP_NAME] : null;
        $type = isset($_FILES[self::FILE_INDEX][self::FILE_TYPE]) ? $_FILES[self::FILE_INDEX][self::FILE_TYPE] : null;
        $size = isset($_FILES[self::FILE_INDEX][self::FILE_SIZE]) ? $_FILES[self::FILE_INDEX][self::FILE_SIZE] : null;
        $name = isset($_FILES[self::FILE_INDEX][self::FILE_NAME]) ? $_FILES[self::FILE_INDEX][self::FILE_NAME] : null;
        $extension = self::define_extension($type);

        if($size > self::LIMIT_SIZE)
            return false;

        if(($extension === self::JPEG && exif_imagetype($tmp_name) == IMAGETYPE_JPEG) ||
                ($extension === self::GIF && exif_imagetype($tmp_name) == IMAGETYPE_GIF) ||
                    ($extension === self::PNG && exif_imagetype($tmp_name) == IMAGETYPE_PNG)){
            $svr_filename =  uniqid() .".".$extension;
            $image_server_name = self::UPLOAD_DIR . $svr_filename;
            if(!move_uploaded_file($tmp_name, $image_server_name))
                return false;
            return $svr_filename;
        }
        return false;
    }

    public static function define_extension($type)
    {
        if(preg_match('/^image\/p?jpeg$/i', $type))
            return self::JPEG;
        if(preg_match('/^image\/gif$/i', $type))
            return self::GIF;
        if(preg_match('/^image\/(x-)?png$/i',$type))
            return self::PNG;
        return self::JPEG;
    }
}