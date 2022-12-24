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

    ->get('/about', function() {
        require "src/page/about.html";
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

    ->get('/user/read', function() {
        require "src/page/user/read.php";
    })

    ->get('/user/search', function() {
        require "src/page/user/search.php";
    })

    ->get('/user/profile', function() {
        require "src/page/user/profile.php";
    })

    ->get('/user/setup', function() {
        require "src/page/user/setup.php";
    })

    ->get('/user/private', function() {
        require "src/page/user/private.php";
    })

    ->get('/user/saved', function() {
        require "src/page/user/saved.php";
    })

    ->get('/user/pinned', function() {
        require "src/page/user/pinned.php";
    })

    ->get('/user/notification/feature/request', function() {
        require "src/page/user/feature/request.php";
    })

    ->get('/user/notification/feature/history', function() {
        require "src/page/user/feature/history.php";
    })

    ->get('/user/unlisted', function() {
        require "src/page/user/unlisted.php";
    })

    ->get('/user/feedback', function() {
        require "src/page/user/feedback.php";
    })

    ->listen();

    // Routing ends


?>