<?php
    require "config/config.php";

    use Router\Router;
    use Config\Database;

    // Calling it this way because it's the whole class being return, instead of having to store the whole class as a variable and using it to access the methods

    // Note in the get/post methods also, the main class is also being returned

    (new Router(new Database))

    // Pages accessible without session
    ->get('/', function() {
        require "src/page/landing.php";
    })
    
    ->get('/login', function() {
        require "src/page/login.php";
    })

    ->get('/signup', function() {
        require "src/page/signup.php";
    })

    ->get('/forgot', function() {
        require "src/page/forgot.php";
    })

    ->get('/confirm', function() {
        require "src/page/confirm.php";
    })

    ->get('/welcome', function() {
        require "src/page/welcome.php";
    })

    ->get('/password', function() {
        require "src/page/password.php";
    })

    ->get('/help', function() {
        require "src/page/help.php";
    })


    // Pages accessible after the landing, this are the content pages

    ->get('/user/home', function() {
        require "src/page/user/home.php";
    })

    ->get('/user/rank', function() {
        require "src/page/user/rank.php";
    })

    ->get('/user/session', function() {
        require "src/page/user/session.php";
    })

    ->listen();

    // Routing ends
    
    
    $path = $_SERVER['REQUEST_URI'];
    $slash_count = substr_count($path, '/');

    require "src/config/header.php";

    // Add the meta tags
    echo head($slash_count);

    // Show the navbar if person is in user page
    $get_path = explode("/", $path);
    if($get_path[1] === "user"):
        include "src/template/navbar.php";
        include "src/template/quick-notice.php";
    endif;

?>