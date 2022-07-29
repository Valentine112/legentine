<?php
    namespace Src\Config;
    
    class Head {

        public static function tags() {
            $struct = substr_count($_SERVER['REQUEST_URI'], '/');

            $struct -= 1;
            $struct == 0 ? $struct = "" : $struct = str_pad("../", $struct);

            echo
            '<head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="'.$struct.'src/config/config.css">
                <link rel="stylesheet" href="'.$struct.'src/font/fonts.css">
                <script async src="'.$struct.'src/js/service/Func.js"></script>
                <script async src="'.$struct.'src/js/service/Form.js"></script>
                <script async src="'.$struct.'src/js/service/Post.js"></script>
                <script async src="'.$struct.'src/js/service/User.js"></script>
            </head>';
        }
    }
?>