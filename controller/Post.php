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

            // Activites performed even when user isn't logged in
            switch ($data['action']):
                case 'fetch_post':
                    $result = $modelPost->fetch_post(USER);
                    break;

                default:

                    break;

            endswitch;

            // Logged in activities
            // Check if user is logged in
            if(USER['type'] === 2):
            
                switch ($data['action']):
                    
                    case 'create_post':
                        $result = $modelPost->create_post();
                        break;
                    
                    case 'update_post':
                        $result = $modelPost->update_post();
                        break;
                    
                    case 'toggle_comment':
                        $result = $modelPost->toggle_comment();
                        break;
    
                    case 'delete_post':
                        $result = $modelPost->delete_post();
                        break;
    
                    case 'save_post':
                        $result = $modelPost->save_post();

                        break;
                    case 'react':
                        $result = $modelPost->react();

                        break;
                    case 'reader':
                        $result = $modelPost->reader();

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