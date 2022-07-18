<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Model\Post as ModelPost;
    use Config\Authenticate;

    class Post extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            define("USER", Authenticate::check_user());

            (array) $result = [];

            $modelPost = new ModelPost(self::$db, $data, USER['content']);

            switch ($data['action']):
                case 'create_post':
                    if(USER['type'] === 2):
                        $result = $modelPost->create_post();

                    else:
                        $this->type = "warning";
                        $this->status = 0;
                        $this->message = "fill";
                        $this->content = USER['content'];

                        $result = $this->deliver();

                    endif;

                    break;
                
                case 'fetch_post':
                    $result = $modelPost->fetch_post(USER);

                    break;

                default:

                    break;

            endswitch;

            return $result;
        }
    }
?>