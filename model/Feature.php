<?php
    namespace Model;

    use mysqli;
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

    class Feature extends Response {

        private static $db;

        public function __construct(mysqli $db, ?array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;

            return $this;
        }

        public function request() : array {
            // Photo does not belong to user
            $this->type = "error";
            $this->status = 0;
            $this->message = "void";

            $val = $this->data['val'];

            $post = $val['post'];

            // Check if user is the owner of the post
            // Because a user cannot feature on his own post
            $data = [
                "token" => $post,
                "user" => $this->user,
                "needle" => "id",
                "table" => "post"
            ];

            $search = Func::searchDb(self::$db, $data);
            if(!is_int($search)):
                // User is not the owner, check if post exist

                $data = [
                    "token" => $post,
                    "1" => "1",
                    "needle" => "id",
                    "table" => "post"
                ];

                $search = Func::searchDb(self::$db, $data);
                if(is_int($search)):
                    $post = $search;
                    // Post exist

                    $this->type = "success";
                    $this->status = 1;
                    $this->message = "void";

                    // Now check if the user has made the request previously,
                    // If he has, delete it, else, process it
                    $data = [
                        "post" => $post,
                        "user" => $this->user,
                        "needle" => "id",
                        "table" => "feature"
                    ];
        
                    $search = Func::searchDb(self::$db, $data);
                    if(!is_int($search)):
                        // Process the request

                        $subject = [
                            "token",
                            "post",
                            "user",
                            "date",
                            "time"
                        ];

                        $items = [
                            Func::tokenGenerator(),
                            $post,
                            $this->user,
                            Func::dateFormat(),
                            time()
                        ];

                        $inserting = new Insert(self::$db, 'feature', $subject, '');
                        $action = $inserting->push($items, 'siisi');
                        if($action):
                            $this->content = "feature";
                        else:
                            return $action;
                        endif;
                    else:
                        // Delete the previous request

                        $deleting = new Delete(self::$db, "WHERE id = ?, $search");
                        $action = $deleting->proceed('feature');
                        if($action):
                            $this->content = "unfeature";
                        else:
                            return $action;
                        endif;
                    endif;

                else:
                    // Post does not exist
                    $this->message = "fill";
                    $this->content = "Post does not exist";
                endif;
            else:
                // You cannot feature on your own post
                $this->content = "You cannot feature on your own post";
            endif;

            return $this->deliver();
        }

    }
?>