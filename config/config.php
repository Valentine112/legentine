<?php
    require 'vendor/autoload.php';
    ini_set("pcre.jit", "0");
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    // General variables
    define("REGFILE", "log/signup.json");
    define("LOGINFILE", "log/login.json");
    define("FORGOTFILE", "log/forgot.json");

    // Trimming the development url tp function with the router
    $path = $_SERVER['REQUEST_URI'];
    $server_len = strlen($path);
    $_SERVER['REQUEST_URI'] = str_split($path, 24)[1];

    // Deployment, the above *Testing would be commented out

    // Clean the log/json files if a data has lasted for more than 12 hours
    function cleanFile($path, int $duration) {
        $files = json_decode(file_get_contents($path), true);
        // Check if file is not empty
        if(is_array($files)):
            foreach($files as $ind => $file):
                // Check if the array has a time as key
                if(isset($file['time'])):
                    if(time() - $file['time'] >= $duration):
                        unset($files[$ind]);
                    endif;
                endif;
            endforeach;
            file_put_contents($path, json_encode($files));
        endif;
    }

    $twentyfourHrs = (60 * 60) * 12;
    cleanFile(REGFILE, $twentyfourHrs);
    cleanFile(FORGOTFILE, $twentyfourHrs);

    
    // So the file doesn't get too overloaded
?>