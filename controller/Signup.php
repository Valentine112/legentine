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

        public function main(array $data) {
            $signup = new ModelSignup(self::$db, $data);

            print_r($this->sendJSON($signup->main()));
        }

    }

?>