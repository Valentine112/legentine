<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Config\Authenticate;

    class Live extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            define("USER", Authenticate::check_user());

            (array) $result = [];

            return $this->deliver();
        }
    }

?>