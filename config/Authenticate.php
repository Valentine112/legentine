<?php
    namespace Config;
    
    use Config\Database;
    use Query\Select;
    use Service\Func;
    // Check if user is logged in

    class Authenticate{

        public static function check_user() {
            $result = [
                "type" => "",
                "content" => ""
            ];

            if(!empty($_COOKIE['token'])):
                $sessionToken = Func::cleanData($_COOKIE['token'], 'string');

                // Check if the token actually exist
                $selecting = new Select(new Database);
                $selecting->more_details("WHERE token = ?, $sessionToken");
                $action = $selecting->action("user", "logins");

                if($action != null):
                    return $action;
                endif;

                $value = $selecting->pull();
                if($value[1] > 0):
                    $result = [
                        "type" => 2,
                        "content" => $value[0][0]['user']
                    ];

                else:
                    $result = [
                        "type" => 0,
                        "content" => "You need to <a href='../login'>Login</a>"
                    ];

                endif;
            else:
                // If user is exploring
                if(isset($_SESSION['explore'])):
                    $result = [
                        "type" => 1,
                        "content" => "Exploring"
                    ];

                else:
                    $result = [
                        "type" => 0,
                        "content" => "You need to <a href='../login'>Login</a> to perform this action"
                    ];

                endif;

            endif;

            return $result;
        }
    }

    // ENDS
?>