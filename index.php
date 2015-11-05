<?php
foreach (['vendor', 'src'] as $directory) {
    $autoloadfile = implode(DIRECTORY_SEPARATOR,[__DIR__, $directory, "autoload.php"]);
    if(!file_exists($autoloadfile))
        die("Error al inicializar la aplicacion");
    require_once($autoloadfile);
}
src\bootstrap\Application::getInstance();