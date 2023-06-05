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

                    case "profile":
                        $result = $modelMoreData->profile();
                        break;

                    case "saved":
                        $result = $modelMoreData->saved();
                        break;

                    case "notification":
                        $result = $modelMoreData->notification();
                        break;

                    case "privatePost":
                        $result = $modelMoreData->privatePost();
                        break;

                    case "featureRequest":
                        $result = $modelMoreData->featureRequest();
                        break;

                    case "featureHistory":
                        $result = $modelMoreData->featureHistory();
                        break;

                    case "read":
                        $result = $modelMoreData->comments();
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