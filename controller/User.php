<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Model\User as ModelUser;

    class User extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            (array) $result = [];

            print_r(USER);

            if(is_int(USER)):
                $modelUser = new ModelUser(self::$db, $data, USER);

                switch ($data['action']):
                    case 'create_post':
                        $result = $modelUser->create_post();
                        break;
                    
                    default:

                        break;

                endswitch;
            endif;

            return $result;
        }
    }
?>