<?php
    namespace Model;

    use mysqli;
    use Query\{
        Insert,
        Select
    };
    use Service\{
        Response,
        Func
    };

    class Comment extends Response {

        public static $db;

        public function __construct(mysqli $db, ?array $data, int|string $user)
        {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;

            return $this;
        }

        public function fetch_comment($post) : array {
            $result = [
                'content' => [],
                'other' => []
            ];

            $this->selecting->more_details("WHERE post = ? LIMIT 20, $post");
            $action = $this->selecting->action("*", "comments");
            $this->selecting->reset();

            if($action != null) return $action;

            $value = $this->selecting->pull();


            // Fetch the comment owner details
            foreach($value[0] as $val):
                $other = $val['user'];

                $this->selecting->more_details("WHERE id = ?, $other");
                $action = $this->selecting->action("fullname, username, photo, rating", "user");
                $this->selecting->reset();

                if($action != null) return $action;

                array_push($result['content'], $val);
                array_push($result['other'], $this->selecting->pull()[0][0]);

            endforeach;

            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            return $this->deliver();
        }

        public function create_comment() : array {
            $val = $this->data['val'];

            // Fetch post first
            $post = new Post(self::$db, null, "");
            $item = [
                "token" => $val['filter'],
                "table" => "post"
            ];

            $post = $post->fetchId($item)[0][0]['id'];

            if(strlen(trim($val['content'])) > 0):
                $subject = [
                    "token",
                    "post",
                    "user",
                    "comment",
                    "date",
                    "time"
                ];

                $items = [
                    Func::tokenGenerator(),
                    $post,
                    $this->user,
                    $val['content'],
                    Func::dateFormat(),
                    time()
                ];

                $inserting = new Insert(self::$db, "comments", $subject, "");
                $action = $inserting->push($items, 'siissi');
                if($action):
                    // Fetch the comment that was made and save it in content

                    $this->status = 1;
                    $this->message = "void";
                    $this->content = ;
                else:
                    return $action;
                endif;

            else:
                $this->type = "warning";
                $this->status = 0;
                $this->message = "fill";
                $this->content = "Comment is empty";

            endif;


            return $this->deliver();
        }
    }
?>