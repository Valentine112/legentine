<?php
    namespace Model;

    use mysqli;
    use Model\Post;
    use Query\{
        Delete,
        Insert,
        Select,
        Update
    };
    use Service\{
        Response,
        Func
    };

    // Other in feature is the person that the request is been sent to
    // User is the person who made the request to be feature

    class MoreData extends Response {

        private static $db;

        public function __construct(mysqli $db, ?array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;

            return $this;
        }

        public function morePost() : array {
            // Photo does not belong to user
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $filter = $this->data['val']['filter'];
            $more = $this->data['val']['more'];

            // Fetch post id
            $data = [
                "token" => $filter,
                "1" => "1",
                "needle" => "id",
                "table" => "post"
            ];

            $post = Func::searchDb(self::$db, $data, "AND");
            if(is_int($post)):

                $blocked_query = Func::blockedUsers(self::$db, $this->user)[0];$blocked_result = Func::blockedUsers(self::$db, $this->user)[1];

                // Fetch post whose id is greater than the last post id
                if($more === ""):
                    $this->selecting->more_details("WHERE id < ? $blocked_query ORDER BY id DESC LIMIT 20# $post# $blocked_result");

                else:
                    $this->selecting->more_details("WHERE id < ? AND category = ? $blocked_query ORDER BY id DESC LIMIT 20# $post# $more# $blocked_result");

                endif;

                $action = $this->selecting->action("*", "post");
                $this->selecting->reset();

                if($action != null) return $action;

                $data = $this->selecting->pull();

                // Configure the data using Post config_data method
                // This would arrange and sort the data properly
                
                $result = (new Post(self::$db, null, ""))->config_data([], $data[0], "user", $this->user);

                $this->content = $result;
    
                return $this->deliver();

            else:
                return $post;
            endif;

            return $this->deliver();

        }

        public function profile() : array {
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $filter = $this->data['val']['filter'];
            $more = $this->data['val']['more'];

            if($more === "photos"):
                // Search last photo ID
                $data = [
                    "token" => $filter,
                    "1" => "1",
                    "needle" => "id",
                    "table" => "gallery"
                ];

                $photo = Func::searchDb(self::$db, $data, "AND");
                if(is_int($photo)):
                    
                endif;

            elseif($more === "notes"):

            endif;

            return $this->deliver();
        }

    }

?>