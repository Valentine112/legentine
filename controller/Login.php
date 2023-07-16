<?php

    namespace Controller;

    use mysqli;
    use Model\{
        Login as ModelLogin,
        Signup as ModelSignup
    };
    use Service\Response;

    class Login extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            (array) $result = [];

            $login = new ModelLogin(self::$db, $data);
            $signup = new ModelSignup(self::$db, $data, LOGINFILE);

            switch ($data['action']):
                
                case 'login':
                    $result = $login->verify();
                    break;

                case 'confirm':
                    $result = $signup->check_code($data['val'], null);
                    break;

                case 'resend':
                    $result = $signup->resend(LOGINFILE);
                    break;
                
                case 'forgot':
                    $result = $login->forgot();
                    break;

                case 'forgot-checkCode':
                    $result = $signup->check_code($data['val'], "!reset");
                    break;

                case 'new-password':
                    $result = $login->update_password();
                    break;

                case 'addDevice':
                    $result = $login->addNewDevice($data['val']['user']);
                    break;

                case 'explore':
                    $result = $login->explore();
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