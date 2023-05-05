<?php
    header('Cache-Control: no-store');
    header('Content-Type: text/event-stream');

    require "config/config.php";

    use Config\Database;
    use Router\Request2;
    use Controller\Live;

    $db = new Database;
    $request = new Request2($db);

    echo "event: user\n";
    echo "data: ".json_encode(['hello'])."";
    echo PHP_EOL.PHP_EOL;

    ob_end_flush();
    flush();
/*
    $request->is_get(
        'notification',
        [Live::class, 'main']
    );

    $request->listen();

    echo "event: user\n";
    echo "data: ".json_encode(['hello'])."";
    echo PHP_EOL.PHP_EOL;

    ob_end_flush();
    flush();*/

    //$request->send(1);

?>