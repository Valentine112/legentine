<?php
    namespace Config;
    
    use Config\Database;
    use Query\Select;
    // Check if user is logged in

    class Authenticate{

        public static function check_user() {
            $user = "";

            if(!empty($_COOKIE['token'])):
                $sessionToken = $_COOKIE['token'];

                $selecting = new Select(new Database);
                $selecting->more_details("WHERE token = ?, $sessionToken");
                $action = $selecting->action("id", "logins");

                if($action != null):
                    return $action;
                endif;

                $value = $selecting->pull();
                if($value[1] > 0):
                    $user = $value[0][0]['id'];

                    return $user;
                else:
                    return false;

                endif;
            else:
                // If user is exploring
                if(isset($_SESSION['explore'])):
                    $user = "explore";
                else:
                    return false;
                endif;

            endif;
        }
    }

    // ENDS
?>