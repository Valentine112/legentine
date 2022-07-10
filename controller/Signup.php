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
            (array) $result = [];

            $signup = new ModelSignup(self::$db, $data, REGFILE);

            switch ($data['action']):
                case 'verify':
                    $result = $signup->verify();
                    break;
                
                case 'confirm':
                    $result = $signup->confirm();
                    break;

                case 'resend':
                    $result = $signup->resend(REGFILE);
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