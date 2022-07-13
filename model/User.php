<?php
    namespace Model;

    use mysqli;
    use Query\Select;
    use Service\Response;

    class User extends Response{

        private static $db;

        public function __construct(mysqli $db, array $data) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
        }

        public function create_post() : array {

            

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