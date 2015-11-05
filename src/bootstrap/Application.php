<?php
/**
 * Created by PhpStorm.
 * User: rulosan
 * Date: 04/11/15
 * Time: 14:24
 */

namespace src\bootstrap;

use ORM;
use PDO;
use Slim\Slim;

use src\libs\Logger;
use src\libs\Authorization;

class Application
{
    const DB_INI_FILE   = "./database.ini";
    const SLIM_NAME     = "name";
    const SLIM_PATTERN  = "pattern";
    const SLIM_METHODS  = "methods";
    const SLIM_RESOLVER = "resolver";
    const SLIM_AUTH     = "auth";
    const SLIM_CONDS    = "conditions";


    private $slim_settings = [
        "debug"               => true,
        "mode"                => "development",
        "log.enabled"         => true,
        "cookies.encrypt"     => true,
        "cookies.domain"      => "secureprogramming.dev",
        "cookies.lifetime"    => "10 years",
        "cookies.path"        => "/",
        "cookies.secure"      => false,
        "cookies.httponly"    => true,
        #"cookies.secret_key"  => "1234567890",
        #"cookies.cipher"      => MCRYPT_RIJNDAEL_256,
        #"cookies.cipher_mode" => MCRYPT_MODE_CBC,
        "http.version"        => "1.1",
        "templates.path"      => "./src/templates",
    ];


    private static $instance;

    private function __construct()
    {
        $this->load_orm();
        $this->load_slim();
    }

    public static function getInstance()
    {
        if(!self::$instance instanceof self){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function load_database_file()
    {
        if(!file_exists(self::DB_INI_FILE))
            die("Error en la inicializacion de la aplicacion");
        return parse_ini_file(self::DB_INI_FILE);
    }

    public function load_orm()
    {
        $db = $this->load_database_file();
        ORM::configure(sprintf($db["dsn"],$db["host"],$db["name"]), null, $db["tag"]);
        ORM::configure('username', $db["user"], $db["tag"]);
        ORM::configure('password', $db["password"], $db["tag"]);
        ORM::configure('return_result_sets', true, $db["tag"]);
        ORM::configure('logging', true, $db["tag"]);
        ORM::configure('driver_options', [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'], $db["tag"]);
        ORM::configure('logger', Logger::log_sql_query($db["name"]),$db["tag"]);
    }

    public function load_slim()
    {
        $slim = new Slim($this->slim_settings);
        foreach ($this->routes as $old_route) {
            $name       = isset($old_route[self::SLIM_NAME]) && is_string($old_route[self::SLIM_NAME]) ? $old_route[self::SLIM_NAME] : null;
            $pattern    = isset($old_route[self::SLIM_PATTERN]) && is_string($old_route[self::SLIM_PATTERN]) ? $old_route[self::SLIM_PATTERN] : null;
            $methods    = isset($old_route[self::SLIM_METHODS]) && is_array($old_route[self::SLIM_METHODS]) ? $old_route[self::SLIM_METHODS] : null;
            $resolver   = isset($old_route[self::SLIM_RESOLVER]) && is_callable($old_route[self::SLIM_RESOLVER]) ? $old_route[self::SLIM_RESOLVER] : null;
            $auth       = isset($old_route[self::SLIM_AUTH]) && is_string(self::SLIM_AUTH) ? $old_route[self::SLIM_AUTH] : null;
            $conditions = isset($old_route[self::SLIM_CONDS]) && is_array($old_route[self::SLIM_CONDS]) ? $old_route[self::SLIM_CONDS] : null;

            if ($name == null || $pattern == null || $methods == null || $resolver == null || $auth == null)
                continue;

            $route = $slim->map($pattern, Authorization::hook($auth), $resolver);
            $route->via($methods);
            $route->name($name);

            if($conditions != null)
                $route->conditions($conditions);
        }
        $slim->run();
    }

    private $routes = [
        [
            self::SLIM_NAME     => "index",
            self::SLIM_PATTERN  => "/",
            self::SLIM_METHODS  => ["GET"],
            self::SLIM_RESOLVER => ["src\\controllers\\MainController", "show_index"],
            self::SLIM_AUTH     => "publico",
        ],

        [
            self::SLIM_NAME     => "sql_injection",
            self::SLIM_PATTERN  => "/sqli/vuln",
            self::SLIM_METHODS  => ["GET"],
            self::SLIM_RESOLVER => ["src\\controllers\\SQLInjectionController", "vulnerable"],
            self::SLIM_AUTH     => "publico",
        ],

        [
            self::SLIM_NAME     => "sql_injection",
            self::SLIM_PATTERN  => "/sqli/sec",
            self::SLIM_METHODS  => ["GET"],
            self::SLIM_RESOLVER => ["src\\controllers\\SQLInjectionController", "secure"],
            self::SLIM_AUTH     => "publico",
        ],

        [
            self::SLIM_NAME     => "xss_store",
            self::SLIM_PATTERN  => "/xss/vuln",
            self::SLIM_METHODS  => ["GET","POST"],
            self::SLIM_RESOLVER => ["src\\controllers\\XSSController", "salvar_compra"],
            self::SLIM_AUTH     => "publico",
        ],

        [
            self::SLIM_NAME     => "comand_injection",
            self::SLIM_PATTERN  => "/cmd/inj",
            self::SLIM_METHODS  => ["GET"],
            self::SLIM_RESOLVER => ["src\\controllers\\CommandInjectionController", "ping"],
            self::SLIM_AUTH     => "publico",
        ],

        [
            self::SLIM_NAME     => "other_vulns",
            self::SLIM_PATTERN  => "/lfi",
            self::SLIM_METHODS  => ["GET"],
            self::SLIM_RESOLVER => ["src\\controllers\\OtherVulnerabilitiesController", "show_lfi"],
            self::SLIM_AUTH     => "publico",
        ],

        [
            self::SLIM_NAME     => "other_vulns_1",
            self::SLIM_PATTERN  => "/rfi",
            self::SLIM_METHODS  => ["GET","POST"],
            self::SLIM_RESOLVER => ["src\\controllers\\OtherVulnerabilitiesController", "show_rfi"],
            self::SLIM_AUTH     => "publico",
        ],

        [
            self::SLIM_NAME     => "sec_rfi",
            self::SLIM_PATTERN  => "/sec/rfi",
            self::SLIM_METHODS  => ["GET", "POST"],
            self::SLIM_RESOLVER => ["src\\controllers\\OtherVulnerabilitiesController", "show_sec_upload"],
            self::SLIM_AUTH     => "publico",
        ],
    ];
}