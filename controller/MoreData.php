<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Config\Authenticate;

    use Model\MoreData as ModelMoreData;

    class MoreData extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            define("USER", Authenticate::check_user());

            (array) $result = [];

            $modelMoreData = new ModelMoreData(self::$db, $data, USER['content']);
            
            // Logged in activities
            // Check if user is logged in
            if(USER['type'] === 2):

                switch ($data['action']):

                    case "more_post":
                        $result = $modelMoreData->morePost();
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