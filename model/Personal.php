<?php
    namespace Model;

    use mysqli;
    use Service\{
        Response,
        Func,
    };

    use Query\{
        Select,
        Update
    };

    use Model\Post;

    class Personal extends Response {

        private static $db;

        public function __construct(mysqli $db, array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;
        }

        public function checkPassword(string|int $param, string $needle) : bool {
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => $needle,
                "table" => "user"
            ];

            $search = Func::searchDb(self::$db, $data, "AND");

            if(!empty($search)):
                if(password_verify($param, $search)):
                    return TRUE;
                else:
                    return FALSE;
                endif;
            endif;
        }

        public function create() : array {
            // Initialize the status and type first
            $this->type = "error";
            $this->status = 0;

            $val = $this->data['val'];
            $pin = $val['pin'];
            $password = $val['password'];

            if($this->checkPassword($password, "password")):

                if(is_numeric($pin) && strlen(trim($pin)) >= 4):
                    // Hash the pin
                    $hashed_pin = password_hash($pin, PASSWORD_DEFAULT);

                    // Proceed to create the pin
                    $updating = new Update(self::$db, "SET privateCode = ? WHERE id = ?# $hashed_pin# $this->user");
                    $action = $updating->mutate('si', 'user');
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
            else:
                $this->message = "fill";
                $this->content = "Acoount password is incorrect";
            endif;

            return $this->deliver();
        }

        public function login() : array {
            // Initialize the status and type first
            $this->type = "error";
            $this->status = 0;

            $val = $this->data['val'];
            $pin = $val['pin'];

            if($this->checkPassword($pin, "privateCode")):
                $this->type = "success";
                $this->status = 1;
                $this->message = "void";
                $this->content = "Successfully created pin";

            else:
                $this->message = "fill";
                $this->content = "Pin is incorrect";
            endif;

            return $this->deliver();
        }

        public function forgot() : array {
            // Called the create method since it and the forgot are practically the same thing
            return $this->create();
        }

        public function fetch() : array {
            (int) $one = 1;

            $this->selecting->more_details("WHERE privacy = ? AND user = ?, $one, $this->user");

            $action = $this->selecting->action("*", "post");
            $this->selecting->reset();

            if($action != null) return $action;

            $post = $this->selecting->pull();

            $ModelPost = new Post(self::$db, null, $this->user);
            $result = $ModelPost->config_data("", $post[0], "user", $this->user);

            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            return $this->deliver();
        }

    }

?>