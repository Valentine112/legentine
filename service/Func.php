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
            return uniqid("").time();
        }
    
        public static function deviceInfo() : array {
            $server = $_SERVER['HTTP_USER_AGENT'];
    
            $server1 = strpos($server, "(") + 1;
            $server2 = strpos($server, ")") - $server1;
        
            $server3 = substr($server, $server1, $server2);
    
            return [$server3, $_SERVER['REMOTE_ADDR']];
        }

        public static function dateFormat() : string {
            return Date('Y-m-d h:i:sa');
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
                'name' => 'Ahid'
            ];

        }
    }

?>