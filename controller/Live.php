<?php
    namespace Controller;

    use mysqli;
    use Service\{
        Response,
        Func
    };
    use Config\Authenticate;
    use Model\Notification as ModelNotification;

    class Live extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            define("USER", Authenticate::check_user());

            (array) $result = [];

            $modelNotification = new ModelNotification(self::$db, $data, USER['content']);

            // Logged in activities
            // Check if user is logged in
            if(USER['type'] === 2):

                switch ($data['action']):

                    case "liveNotification":
                        // Check if quiet mode is on for the user
                        // If it's 1, do not fetch the live notifications
                        // Else proceed to fetch them
                        $data = [
                            "id" => USER['content'],
                            "1" => "1",
                            "needle" => "quiet",
                            "table" => "user"
                        ];

                        $quiet = Func::searchDb(self::$db, $data, "AND");
  
                        // Check if the query was successful
                        if(is_int($quiet)):
                            // If zero, meaning notifications can pop
                            if($quiet === 0):
                                $result = $modelNotification->liveNotification();
                            else:
                                // Else notifications should be paused

                                $this->type = "success";
                                $this->status = 1;
                                $this->message = "void";
                                $this->content = [];
                                $this->more = USER['content'];

                                $result = $this->deliver();
                            endif;
                        else:
                            $result = $quiet;
                        endif;

                        break;

                    default:
                        break;

                endswitch;
            else:
                $this->type = "warning";
                $this->status = 0;
                $this->message = "fill";
                $this->content = USER['content'];
                $this->more = USER['content'];

                $result = $this->deliver();

            endif;

            return $result;

        }
    }

?>