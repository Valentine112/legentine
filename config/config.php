<?php
    require 'vendor/autoload.php';
    ini_set("pcre.jit", "0");
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    //Testing
    $path = $_SERVER['REQUEST_URI'];
    $server_len = strlen($path);
    $_SERVER['REQUEST_URI'] = str_split($path, 24)[1];
    

    // Deployment, the above *Testing would be commented out
?>