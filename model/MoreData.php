<?php
    namespace Model;

    use mysqli;
    use Config\Authenticate;
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
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $result = [];
            $lastElement = $this->data['val']['lastElement'];
            $filter = $this->data['val']['filter'];
            (int) $zero = 0;

            // Fetch post id
            $data = [
                "token" => $lastElement,
                "1" => "1",
                "needle" => "id",
                "table" => "post"
            ];

            $post = Func::searchDb(self::$db, $data, "AND");
            if(is_int($post)):

                $blocked_query = Func::blockedUsers(self::$db, $this->user)[0];$blocked_result = Func::blockedUsers(self::$db, $this->user)[1];

                // Fetch post whose id is greater than the last post id
                if($filter === ""):
                    $this->selecting->more_details("WHERE id < ? AND privacy = ? $blocked_query ORDER BY id DESC LIMIT 20# $post# $zero# $blocked_result");

                else:
                    $this->selecting->more_details("WHERE id < ? AND privacy = ? AND category = ? $blocked_query ORDER BY id DESC LIMIT 20# $post# $zero# $filter# $blocked_result");

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

            $result = [];

            $lastElement = $this->data['val']['lastElement'];
            $filter = $this->data['val']['filter'];
            $person = $this->data['val']['more'];

            if($person === "") $person = $this->user;

            if($filter === "photos"):
                // Search last photo ID
                $data = [
                    "token" => $lastElement,
                    "1" => "1",
                    "needle" => "id",
                    "table" => "gallery"
                ];

                $photo = Func::searchDb(self::$db, $data, "AND");
                if(is_int($photo)):
                    //print_r($photo);
                    $this->selecting->more_details("WHERE id < ? AND user = ? ORDER BY id DESC LIMIT 20# $photo# $person");
                    $action = $this->selecting->action("*", "gallery");
                    $this->selecting->reset();

                    if($action != null) return $action;
        
                    $value = $this->selecting->pull()[0];
                    foreach($value as $val):
                        $arr['content'] = $val;
                        $arr['self'] = $this->user;
                        $arr['section'] = "photos";
        
                        array_push($result, $arr);
                    endforeach;
                endif;

            elseif($filter === "notes"):
                // Fetch post id
                $data = [
                    "token" => $lastElement,
                    "1" => "1",
                    "needle" => "id",
                    "table" => "post"
                ];
    
                $post = Func::searchDb(self::$db, $data, "AND");
                if(is_int($post)):
                    $this->selecting->more_details("WHERE id < ? AND user = ? ORDER BY id DESC LIMIT 20# $post# $person");

                    $action = $this->selecting->action("*", "post");
                    $this->selecting->reset();
    
                    if($action != null) return $action;
    
                    $data = $this->selecting->pull();
    
                    // Configure the data using Post config_data method
                    // This would arrange and sort the data properly
                    
                    $result = (new Post(self::$db, null, ""))->config_data([], $data[0], "user", $this->user);
                endif;
            endif;

            $this->content = $result;

            return $this->deliver();
        }

        public function saved() : array {
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $result = [];
            $lastElement = $this->data['val']['lastElement'];

            // Fetch post id
            $data = [
                "token" => $lastElement,
                "1" => "1",
                "needle" => "id",
                "table" => "saved"
            ];

            $saved = Func::searchDb(self::$db, $data, "AND");
            if(is_int($saved)):

                $data = [
                    "val" => [
                        "filter" => $saved,
                        "from" => "saved",
                        "query" => "AND id < ?",
                        "new" => 1
                    ]
                ];

                return (new Post(self::$db, $data, $this->user))->fetch_post(Authenticate::check_user());
                
            else:
                return $saved;
            endif;

            //return $this->deliver();
        }

    }

?>