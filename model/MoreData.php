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

                $data = [
                    "val" => [
                        "value" => $post,
                        "from" => "home",
                        "filter" => $filter,
                        "query" => "AND id < ?",
                        "new" => 1
                    ]
                ];

                $post = new Post(self::$db, $data, $this->user);
                $this->content = $post->fetch_post(Authenticate::check_user())['content'];

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


                    $data = [
                        "val" => [
                            "value" => $photo,
                            "from" => "profile",
                            "user" => $person,
                            "query" => "AND id < ?",
                            "new" => 1
                        ]
                    ];

                    $user = new User(self::$db, $data, $this->user);
                    $result = $user->fetchPhotos(Authenticate::check_user())['content'];

                    /*$this->selecting->more_details("WHERE id < ? AND user = ? ORDER BY id DESC LIMIT 20# $photo# $person");
                    $action = $this->selecting->action("*", "gallery");
                    $this->selecting->reset();

                    if($action != null) return $action;
        
                    $value = $this->selecting->pull()[0];
                    foreach($value as $val):
                        $arr['content'] = $val;
                        $arr['self'] = $this->user;
                        $arr['section'] = "photos";
        
                        array_push($result, $arr);
                    endforeach;*/
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

                    $data = [
                        "val" => [
                            "value" => $post,
                            "from" => "profile",
                            "filter" => $filter,
                            "more" => $person,
                            "query" => "AND id < ?",
                            "new" => 1
                        ]
                    ];
    
                    $post = new Post(self::$db, $data, $this->user);
                    $result = $post->fetch_post(Authenticate::check_user())['content'];

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

            // Fetch saved id
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
                        "value" => $saved,
                        "from" => "saved",
                        "query" => "AND id < ?",
                        "new" => 1,
                        "filter" => ""
                    ]
                ];

                $result = (new Post(self::$db, $data, $this->user))->fetch_post(Authenticate::check_user());

            else:
                return $saved;
            endif;

            $this->content = $result['content'];

            return $this->deliver();
        }

        public function notification() : array {
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $result = [];
            $lastElement = $this->data['val']['lastElement'];

            // Fetch saved id
            $data = [
                "token" => $lastElement,
                "1" => "1",
                "needle" => "id",
                "table" => "notification"
            ];

            $notification = Func::searchDb(self::$db, $data, "AND");

            if(is_int($notification)):
                $data = [
                    "filter" => $notification,
                    "query" => "AND id < ?",
                    "from" => "notification",
                    "new" => 1
                ];

                $fetchNotification = new Notification(self::$db, [], $this->user);
                $this->content = $fetchNotification->fetchNotification($data)['content'];

            else:
                return $notification;
            endif;

            return $this->deliver();

        }

        public function privatePost() : array {
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $result = [];
            $lastElement = $this->data['val']['lastElement'];
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
                $data = [
                    "filter" => $post,
                    "query" => "AND id < ?",
                    "from" => "post",
                    "new" => 1
                ];

                $personal = new Personal(self::$db, [], $this->user);
                $this->content = $personal->fetch($data)['content'];
            else:
                return $post;
            endif;

            return $this->deliver();
        }

    }

?>