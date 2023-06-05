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

        public function featureRequest() : array {
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $lastElement = $this->data['val']['lastElement'];

            // Fetch post id
            $data = [
                "token" => $lastElement,
                "1" => "1",
                "needle" => "id",
                "table" => "feature"
            ];

            $feature = Func::searchDb(self::$db, $data, "AND");
            if(is_int($feature)):
                $data = [
                    "val" => [
                        "value" => $feature,
                        "query" => "AND id < ?",
                        "type" => "request",
                        "from" => "featureRequest",
                        "new" => 1
                    ]
                ];

                $featureModel = new Feature(self::$db, $data, $this->user);
                $this->content = $featureModel->fetchRequest(null)['content'];

            else:
                return $feature;
            endif;

            return $this->deliver();
        }

        public function featureHistory() : array {
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $lastElement = $this->data['val']['lastElement'];

            // Fetch post id
            $data = [
                "token" => $lastElement,
                "1" => "1",
                "needle" => "id",
                "table" => "history"
            ];

            $history = Func::searchDb(self::$db, $data, "AND");

            if(is_int($history)):
                $data = [
                    "val" => [
                        "value" => $history,
                        "query" => "AND id < ?",
                        "type" => "request",
                        "from" => "featureHistory",
                        "new" => 1
                    ]
                ];

                $featureModel = new Feature(self::$db, $data, $this->user);
                $this->content = $featureModel->fetchHistory()['content'];

            else:
                return $feature;
            endif;

            return $this->deliver();
        }

        public function comment() : array|bool {
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $lastElement = $this->data['val']['lastElement'];
            $post = $this->data['val']['more'];

            // Fetch comment id
            $data = [
                "token" => $lastElement,
                "1" => "1",
                "needle" => "id",
                "table" => "comments"
            ];

            $comment = Func::searchDb(self::$db, $data, "AND");

            // Fetch post id
            $data['token'] = $post;
            $data['table'] = "post";
            $post = Func::searchDb(self::$db, $data, "AND");

            // Check if post is int
            // If it's not, it means that the post does not exist
            if(is_int($post)):
                if(is_int($comment)):
                    $data = [
                        "val" => [
                            "value" => $comment,
                            "query" => "AND id < ?",
                            "type" => "request",
                            "from" => "read",
                            "new" => 1
                        ]
                    ];

                    $commentModel = new Comment(self::$db, $data, $this->user);
                    $this->content = $commentModel->fetch_comment($post, $data)['content'];

                else:
                    return $comment;
                endif;
            else:
                return $post;
            endif;

            return $this->deliver();
        }

        public function reply() : array|bool {
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $lastElement = $this->data['val']['lastElement'];
            $commentToken = $this->data['val']['more'];

            // Fetch reply id
            $data = [
                "token" => $lastElement,
                "1" => "1",
                "needle" => "id",
                "table" => "replies"
            ];

            $reply = Func::searchDb(self::$db, $data, "AND");

            // Fetch comment id
            $data['token'] = $commentToken;
            $data['table'] = "comments";

            $comment = Func::searchDb(self::$db, $data, "AND");

            if(is_int($comment)):
                if(is_int($reply)):
                    $data = [
                        "val" => [
                            "value" => $reply,
                            "query" => "AND id < ?",
                            "comment" => $commentToken,
                            "type" => "request",
                            "from" => "read",
                            "new" => 1
                        ]
                    ];

                    $commentModel = new Comment(self::$db, $data, $this->user);
                    $this->content = $commentModel->fetch_reply($data)['content'];
                else:
                    return $reply;
                endif;
            else:
                return $comment;
            endif;

            return $this->deliver();

        }

    }

?>