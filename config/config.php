<?php
    require 'vendor/autoload.php';

    use Service\FileHandling;

    ini_set("pcre.jit", "0");
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    // General variables
    define("REGFILE", "log/signup.json");
    define("LOGINFILE", "log/login.json");
    define("FORGOTFILE", "log/forgot.json");
    define("RANDOMS", "log/randoms.json");

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


    // Check what session type is active
    $session_type = null;

    // If user is exploring
    if(isset($_SESSION['explore'])):
        $session_type = "explore";

    // If user isn't performing any
    else:
        $session_type = null;

    endif;

    // Getting the random people and post that would be displayed in the search menu
    $fileHandling = new FileHandling(RANDOMS);
    $file = json_decode($fileHandling->fetchFile(), true);

    // Check if file is empty


?>