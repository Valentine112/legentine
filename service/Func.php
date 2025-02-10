<?php
    namespace Service;

    use DateTime;
    use mysqli;
    use Query\Select;

    class Func {

        public static function cleanData($data, string $type) {
            
            switch ($type) {
                case 'string':
                    $data = htmlspecialchars(trim(stripcslashes($data)), ENT_NOQUOTES);
                    break;
    
                case 'integer':
                    (filter_var($data, FILTER_VALIDATE_INT)) ? (int) $data = $data : $data = "error";
    
                    break;
    
                case 'email':
                    $data = filter_var($data, FILTER_SANITIZE_EMAIL);
                    (filter_var($data, FILTER_VALIDATE_EMAIL)) ? $data = $data : $data = "error";
    
                    break;
                default:
                    return "error";
                    break;
            }
    
            return $data;
        }

        public static function tokenGenerator() : string {
            return bin2hex(random_bytes(10)).time();
        }
    
        public static function deviceInfo() : array {
            $server = $_SERVER['HTTP_USER_AGENT'];
    
            $server1 = strpos($server, "(") + 1;
            $server2 = strpos($server, ")") - $server1;
        
            $server3 = substr($server, $server1, $server2);
    
            return [$server3, $_SERVER['REMOTE_ADDR']];
        }

        public static function dateFormat() : string {
            $date = new DateTime('now');
            $date = $date->format('Y-m-d\TH:i:sp');

            return $date;
        }
        public static function dateParts(string $date) : array {
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 2);
            $day = substr($date, 8, 2);

            return [$year, $month, $day];
        }
        public static function sortAsc($a, $b){
            $t1 = $a['time'];
            $t2 = $b['time'];
            return $t2 - $t1;
        }

        public static function email_config() : array {
            return [
                'id' => 'i@egophren.com',
                'pass' => 'Anthonyval',
                'name' => 'egoPhren',
                'host' => 'mail.egophren.com'
            ];

        }

        public static function searchObject(array $data, string|int $needle, string $key) : array {
            /**
             * Search for needly in object
             * If 1 exist, then the item is found
             * Else, Item does not exist in object
             */
            $exist = [];
            if(!empty($data)):
                foreach($data as $val):
                    if($val[$key] == $needle):
                        array_push($exist, 1);
                    else:
                        array_push($exist, 0);
                    endif;

                endforeach;
            endif;

            return $exist;
        }

        public static function searchDb(mysqli $db, array $data, string $expression) {
            $keys = array_keys($data);
            $values = array_values($data);

            $key = $keys[0];
            $key1 = $keys[1];
            $val = $values[0];
            $val1 = $values[1];
            $needle = $data['needle'];
            $table = $data['table'];

            $selecting = new Select($db);
            $selecting->more_details("WHERE $key = ? $expression $key1 = ?# $val# $val1");
            $action = $selecting->action($needle, $table);

            if($action != null) return $action;
            $value = $selecting->pull();
            if($value[1] > 0):
                if($needle === "*"):
                    return $value[0][0];
                else:
                    return $value[0][0][$needle];
                endif;
            else:
                return false;
            endif;
        }

        public static function mention(mysqli $db, string $content, array $data) {
            $status = 1;
            $key = array_keys($data)[0];
            $val = array_values($data)[0];

            $selecting = new Select($db);
            $selecting->more_details("WHERE $key = ?# $val");
            $action = $selecting->action("other", "mentions");
            $selecting->reset();

            if($action != null) return $action;

            $value = $selecting->pull()[0];

            foreach($value as $other):
                $mentioned = $other['other'];
                // Get the username of person
                $selecting->more_details("WHERE id = ?# $mentioned");
                $selecting->action("username", "user");
                $selecting->reset();

                if($action != null) return $action;
    
                $username = $selecting->pull()[0][0]['username'];

                $mention = "@$username";
                $format = "<a href='profile?token=$mentioned' style='color: #ff465b; text-decoration: none;'>$mention</a>";
                $content = str_replace($mention, $format, $content);

            endforeach;

            return $content;
        }

        public static function blockedUsers(mysqli $db, int $user) : array {
            $selecting = new Select($db);

            // Get all the users that has been blocked by this user first
            $selecting->more_details("WHERE user = ?# $user");
            $action = $selecting->action("other", "blocked_users");
            $selecting->reset();

            if($action != null):
                return $action;
            endif;

            $blocked_users = $selecting->pull();
            $blocked_result = [];
            $blocked_query = "";

            // Get all the blocked users and add them to an array
            if($blocked_users[1] > 0):
                foreach($blocked_users[0] as $blocked):
                    array_push($blocked_result, $blocked['other']);
                endforeach;

                // Join all the blocked result with the hash as a divider
                $blocked_result = implode("#", $blocked_result);

                // Create a question mark parameter for the query
                $param = array_fill(1, $blocked_users[1], "?");
                $param = implode(",", $param);
                $blocked_query = "AND user NOT IN ($param)";
            else:
                
                $blocked_query = "AND user NOT IN (?)";
                $blocked_result = " ";
            endif;


            return [$blocked_query, $blocked_result];
        }

        public static function array_column_recursive(array $haystack, $needle) : array {
            $found = [];
            array_walk_recursive($haystack, function($value, $key) use (&$found, $needle) {
                if($key == $needle) $found[] = $value;
            });

            return $found;
        }

    }

?>