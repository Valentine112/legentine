<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Model\Personal as ModelPersonal;
    use Config\Authenticate;

    class Personal extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {

            define("USER", Authenticate::check_user());

            $modelPersonal = new ModelPersonal(self::$db, $data, USER['content']);

            (array) $result = [];

            if(USER['type'] === 2):

                switch($data['action']):

                    case 'create':
                        $result = $modelPersonal->create();

                        break;

                    case 'login':
                        $result = $modelPersonal->login();

                        break;
                    
                    case 'forgot':
                        $result = $modelPersonal->forgot();

                        break;

                    case 'fetch':
                        $result = $modelPersonal->fetch();

                        break;

                    default:

                        break;

                endswitch;

            else:
                $this->type = "warning";
                $this->status = 0;
                $this->message = "fill";
                $this->content = USER['content'];

                $result = $this->deliver();

            endif;

            return $result;
        }

    }
?>