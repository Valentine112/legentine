<?php
    require "config/config.php";

    use Router\Router;
    use Config\Database;

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

    ->get('/help', function() {
        require "src/page/help.php";
    })


    // Pages only accessible when a session is active

    ->get('/post', function() {
        require "src/element/post.php";
    })
    ->listen();

    require "src/config/config.html";
?>