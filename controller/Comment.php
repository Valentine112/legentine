<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Model\Comment as ModelComment;
    use Config\Authenticate;

    class Comment extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            define("USER", Authenticate::check_user());

            (array) $result = [];

            $modelComment = new ModelComment(self::$db, $data, USER['content']);

            // Logged in activities
            // Check if user is logged in
            if(USER['type'] === 2):
                switch ($data['action']):
                    
                    case "create_comment":
                        $result = $modelComment->create_comment();

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