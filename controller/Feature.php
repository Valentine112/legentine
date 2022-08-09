<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Config\Authenticate;

    class Feature extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {

            return $this->deliver();
        }
    }

?>