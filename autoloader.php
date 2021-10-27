<?php
spl_autoload_register(function ($className){
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $classFile = 'vendor'.DIRECTORY_SEPARATOR.$className.'.php';
    if(file_exists($classFile)){
        include_once $classFile;
        return true;
    }
    return false;
});