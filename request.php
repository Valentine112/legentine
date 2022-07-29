<?php
    require "config/config.php";

    use Config\Database;
    use Router\Request;
    use Controller\{
        Signup,
        Login,
        Post,
        User
    };
    use Service\Response;

    $db = new Database;
    $request = new Request($db);

    $request->is_post(
        'signup', 
        [Signup::class, 'main']
    );

    $request->is_post(
        'login',
        [Login::class, 'main']
    );

    $request->is_post(
        'post',
        [Post::class, 'main']
    );

    $request->is_post(
        'user',
        [User::class, 'main']
    );
    
    print_r(Response::sendJSON($request->listen()));


?>