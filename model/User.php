<?php
    namespace Model;

    use mysqli;
    use Query\Select;
    use Service\{
        Response,
        Func
    };

    class User extends Response{

        private static $db;

        public function __construct(mysqli $db, array $data, int $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;
        }

        public static function fetchId(array $data) : array {
            $token = Func::cleanData($data['token'], 'string');
            $table = Func::cleanData($data['table'], 'string');

            $selecting = new Select(self::$db);

            $selecting->more_details("WHERE token = ? LIMIT 1, $token");
            $action = $selecting->action("id", $table);

            if($action != null) return $action;

            return $selecting->pull();
        }

        public function create_post() : array {

            $subject = [
                'id',
                'token',
                'user',
                ...array_keys($this->data['val']),
            ];

            return $this->deliver();
        }

        public function fetch_post() : array {
            (int) $zero = 0;
            // Fetch the post

            // Home 
            /**
             * WHERE private = 0 ORDER BY ID LIMIT 20 DESC; { CONSTANT }
             * 
             * --- LOGGED IN ---
             * Check if post owner is among blocked and send a bool back as part of the json
             * --- EXPLORE ---
             * Don't check, just leave the value as false
             * 
             * --- FILTER ---
             * Where category = ?
             */
            $from = $this->data['val']['from'];

            if($from == "home"):

                // Check if filter is one
                if(!$this->data['val']['filter']):
                    $this->selecting->more_details("WHERE private = ? ORDER BY id LIMIT 20 DESC, $zero");

                else:
                    $filter = $this->data['val']['filter'];
                    $this->selecting->more_details("WHERE private = ? AND category = ? ORDER BY id LIMIT 20 DESC, $zero, $filter");

                endif;

                $action = $this->selecting->action('*', 'post');
                $this->selecting->reset();

                if($action != null) {
                    return $action;
                }

                $value = $this->selecting->pull();

                return $value[0];

            else:

            endif;

            // Rank

            // Profile


            return $this->deliver();
        }
    }

?>