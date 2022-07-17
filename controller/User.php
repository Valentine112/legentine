<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Model\User as ModelUser;
    use Config\Authenticate;

    class User extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            define("USER", Authenticate::check_user());

            (array) $result = [];

            $modelUser = new ModelUser(self::$db, $data, USER['content']);

            switch ($data['action']):
                case 'create_post':
                    if(USER['type'] === 2):
                        $result = $modelUser->create_post();

                    else:
                        $this->type = "warning";
                        $this->status = 0;
                        $this->message = "fill";
                        $this->content = USER['content'];

                        $result = $this->deliver();

                    endif;

                    break;
                
                default:

                    break;

            endswitch;

            return $result;
        }
    }
?>