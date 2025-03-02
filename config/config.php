<?php
    session_start();    
    require 'vendor/autoload.php';

    use Service\FileHandling;

    // ini_set("pcre.jit", "0");
    // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    // General variables
    define("REGFILE", "log/signup.json");
    define("LOGINFILE", "log/login.json");
    define("FORGOTFILE", "log/forgot.json");
    define("RANDOMS", "log/randoms.json");

    //echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
    // Trimming the development url tp function with the router
    $path = $_SERVER['REQUEST_URI'];
    $server_len = strlen($path);
    $_SERVER['REQUEST_URI'] = substr($path, 14, $server_len);

    $referer = "";
    if(isset($_SERVER['HTTP_REFERER'])) $referer = $_SERVER['HTTP_REFERER'];
    $referer = empty($referer) ?: (substr($referer, 30, strlen($referer)));

    //print_r($referer);
    // Deployment, the above *Testing would be commented out

    // Config the authentication for the private post
    if($_SERVER['REQUEST_URI'] != "/user/privatePost"):
        if($_SERVER['REQUEST_URI'] != "/user/privateAccess" || ($_SESSION['private'] != 1 && $_SERVER['REQUEST_URI'] != "/user/privateAccess")):
            $_SESSION['private'] = 0;
        endif;
    endif;

    //print_r($_SERVER);
    
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
    cleanFile(LOGINFILE, $twentyfourHrs);

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

    //echo $_SERVER['REQUEST_URI'];
    // Log user out
    if($_SERVER['REQUEST_URI'] === "/login?action=logout"):
        setcookie("token", "", time() - 3600, "/", "", FALSE, TRUE);
    endif;

    //print_r($_COOKIE);

?>