<?php
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');

    require "config/config.php";

    use Config\Database;
    use Router\Request;
    use Controller\{
        Signup,
        Login,
        Post,
        User,
        Comment,
        Feature,
        Personal,
        Notification,
        MoreData
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

    $request->is_post(
        'comment',
        [Comment::class, 'main']
    );

    $request->is_post(
        'feature',
        [Feature::class, 'main']
    );

    $request->is_post(
        'personal',
        [Personal::class, 'main']
    );

    $request->is_post(
        'notification',
        [Notification::class, 'main']
    );

    $request->is_post(
        'moreData',
        [MoreData::class, 'main']
    );

    $request->is_get(
        'live',
        [Live::class, 'main']
    );
    
    
    print_r(Response::sendJSON($request->listen()));


?>