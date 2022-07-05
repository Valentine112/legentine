<?php
    namespace Controller;

    use mysqli;
    use Model\Signup as ModelSignup;
    use Service\Response;

    class Signup extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            $signup = new ModelSignup(self::$db, $data);

            return $signup->main();
        }

    }

?>