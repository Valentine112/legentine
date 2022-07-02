<?php
    namespace Controller;

    use mysqli;
    use Model\Signup as Register;

    class Signup {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public static function process(array $data) {
            $signup = new Register([]);
            
            print_r($data);
        }

    }

?>