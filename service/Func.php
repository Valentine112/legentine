<?php
    namespace Service;

    class Func {

        public static function cleanData($data, string $type) {
            
            switch ($type) {
                case 'string':
                    $data = htmlspecialchars(trim(stripcslashes($data)));
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
            return Date('m/d/Y');
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
                'name' => 'Legentine'
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
    }

?>