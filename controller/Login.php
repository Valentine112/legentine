<?php

    namespace Controller;

    use mysqli;
    use Model\Login as ModelLogin;
    use Service\Response;

    class Login extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            (array) $result = [];

            $login = new ModelLogin(self::$db, $data);

            switch ($data['action']):
                
                case 'login':
                    $result = $login->verify();
                    break;
                
                default:
                    $this->type = "Controller/Signup/main";
                    $this->status = 0;
                    $this->message = "void";
                    $this->content = "Invalid action provided";

                    $result = $this->deliver();

                    break;

            endswitch;

            return $result;
        }

    }

?>