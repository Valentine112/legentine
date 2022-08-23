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
                'id' => 'legendstime1@gmail.com',
                'pass' => 'Anthonyval',
                'name' => 'Legentine',
                'host' => 'smtp.gmail.com'
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

        public static function searchDb(mysqli $db, $data) {
            $keys = array_keys($data);
            $values = array_values($data);

            $key = $keys[0];
            $key1 = $keys[1];
            $val = $values[0];
            $val1 = $values[1];
            $needle = $data['needle'];
            $table = $data['table'];

            $selecting = new Select($db);
            $selecting->more_details("WHERE $key = ? AND $key1 = ?, $val, $val1");
            $action = $selecting->action($needle, $table);

            if($action != null) return $action;
            $value = $selecting->pull();
            if($value[1] > 0):
                return $value[0][0][$needle];
            else:
                return false;
            endif;
        }

        public static function mention(mysqli $db, string $content, array $data) {
            $key = array_keys($data)[0];
            $val = array_values($data)[0];


            $selecting = new Select($db);
            $selecting->more_details("WHERE $key = ?, $val");
            $action = $selecting->action("other", "mentions");
            $selecting->reset();

            if($action != null) return $action;

            $value = $selecting->pull()[0];

            foreach($value as $other):
                $mentioned = $other['other'];
                // Get the username of person
                $selecting->more_details("WHERE id = ?, $mentioned");
                $selecting->action("username", "user");
                $selecting->reset();

                if($action != null) return $action;
    
                $username = $selecting->pull()[0][0]['username'];


                $mention = "@$username";
                $format = "<a href='profile?id=$mentioned' style='color: #ff465b; text-decoration: none;'>$mention</a>";
                $content = str_replace($mention, $format, $content);
            endforeach;

            return $content;
        }

    }

?>