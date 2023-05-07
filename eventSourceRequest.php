<?php
    header('Cache-Control: no-store');
    header('Content-Type: text/event-stream');

    require "config/config.php";

    use Config\Database;
    use Router\EventSourceRequest;
    use Controller\Live;

    $db = new Database;
    $request = new EventSourceRequest($db);

    $request->is_get(
        'live',
        [Live::class, 'main']
    );

    $request->listen();

    $request->send(1);


?>