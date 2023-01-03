<?php
    namespace Model;

    use mysqli;
    use Service\{
        Response,
        Func,
        Upload
    };

    use Query\{
        Delete,
        Insert,
        Select,
        Update
    };

    class Personal extends Response {

        private static $db;

        public function __construct(mysqli $db, array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;
        }

        public function create() : array {
            // Initialize the status and type first
            $this->type = "error";
            $this->status = 0;

            $val = $this->data['val'];
            $pin = $val['pin'];

            if(is_numeric($pin) && strlen(trim($pin)) >= 4):
                $pin = (int) $pin;
                // Hash the pin
                $hashed_pin = password_hash($pin, PASSWORD_DEFAULT);

                // Proceed to create the pin
                $updating = new Update(self::$db, "SET privateCode = ? WHERE id = ?# $hashed_pin# $this->user");
                $action = $updating->mutate('ii', 'user');
                if($action):
                    $this->type = "success";
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = "Successfully created pin";

                else:
                    return $action;
                endif;

            else:
                $this->message = "fill";
                $this->content = "Pin should be at least four numbers";

            endif;

            return $this->deliver();
        }

    }

?>