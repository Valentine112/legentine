<?php
    namespace Model;

    use mysqli;
    use Query\Insert;
    use Query\Select;
    use Service\{
        Response,
        Func
    };

    class Post extends Response{

        private static $db;

        public function __construct(mysqli $db, array $data, int|string $user) {
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
                'token',
                'user',
                ...array_keys($this->data['val'])
            ];

            $items = [
                Func::tokenGenerator(),
                $this->user,
                ...array_values($this->data['val'])

            ];

            if(!empty($this->data['val']['title']) && !empty($this->data['val']['content'])):
                $inserting = new Insert(self::$db, "post", $subject, "");
                $action = $inserting->push($items, 'sisssi');
                if(is_bool($action) && $action):
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = "Success";

                else:
                    return $action;

                endif;
            else:

            endif;

            return $this->deliver();
        }

        public function fetch_post($session) : array {
            (int) $zero = 0;
            $from = $this->data['val']['from'];
            $filter = $this->data['val']['filter'];

            $post = "";
            $order = "";

            // Determine the order based on the page the request was sent from
            if($from === "rank"):
                $order = "ORDER BY star DESC LIMIT 15";
            else:
                $order = "ORDER BY id DESC LIMIT 20";
            endif;

            // Verify that user is logged in
            if($session['type'] === 2):
                $user = $session['content'];

                // Check where the request is coming from and process
                if($from === "home"):
                    /**
                     * For homepage
                     * Every blocked user wouldn't be displayed
                     * This would only work here
                     */

                    // Get all the users that has been blocked by this user first
                    $this->selecting->more_details("WHERE user = ?, $user");
                    $action = $this->selecting->action("other", "blocked_users");
                    $this->selecting->reset();

                    if($action != null):
                        return $action;
                    endif;

                    $blocked_users = $this->selecting->pull();

                    // Check if there is a filter attached
                    if($filter === ""):
                        $this->selecting->more_details("WHERE privacy = ? $order, $zero");
                    else:
                        $this->selecting->more_details("WHERE privacy = ? AND category = ? $order, $zero, $filter");
                    endif;

                    $action = $this->selecting->action("*", "post");
                    $this->selecting->reset();

                    if($action != null):
                        return $action;
                    endif;

                    $post = $this->selecting->pull();

                    $post = self::remove_blocked($blocked_users, $post[0], "user", $user);

                endif;

            else:

                // If user is not logged in
                $this->selecting->more_details("WHERE privacy = ? $order, $zero");
                $action = $this->selecting->action("*", "post");
                $this->selecting->reset();

                if($action != null):
                    return $action;
                endif;

                $post = [
                    "data" => $this->selecting->pull()[0],
                    "self" => 0
                ];

            endif;

            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $post;

            return $this->deliver();
        }

        public static function remove_blocked(array|string $blocked, array $items, string $key, ?int $more) : array {

            $result = [
                "data" => [],
                "self" => $more
            ];

            foreach ($items as $item):
                if(!in_array($item[$key], $blocked)):

                    array_push($result["data"], $item);
                endif;
            endforeach;

            return $result;
        }
    }

?>