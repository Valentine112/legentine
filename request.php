<?php
    require "config/config.php";

    use Config\Database;
    use Router\Request;
    use Controller\{
        Signup,
        Login
    };

    $db = new Database;
    $request = new Request($db);

    function test() {
        echo "What are we doing here";
    }

    $theFunc = "test";

    $request->is_post('signup', [Signup::class, 'process']);
    $request->is_post('login', $theFunc);


    $request->listen();


?>