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

    $request->is_post(
        'signup', 
        [Signup::class, 'main']
    );

    $request->listen();


?>