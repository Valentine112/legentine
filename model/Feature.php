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

        public function fetchFeature(?int $post) : array {
            // Photo does not belong to user
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $result = [
                'content' => [],
                'self' => $this->user
            ];

            if(is_int($post)):
                $this->selecting->more_details("WHERE post = ?, $post");
            else:
                $type = $this->data['val']['type'];

                if($type === "request"):
                    $this->selecting->more_details("WHERE other = ?, $this->user");
                elseif($type === "history"):
                    $this->selecting->more_details("WHERE user = ? OR other = ?, $this->user, $this->user");
                endif;
            endif;

            $action = $this->selecting->action('*', 'feature');

            $this->selecting->reset();

            if($action != null) return $action;

            $value = $this->selecting->pull();
            foreach($value[0] as $val):
                $tempResult = [];

                // Fetch other
            
                $data = [
                    "id" => $val['other'],
                    "1" => "1",
                    "needle" => "*",
                    "table" => "user"
                ];
    
                $search = Func::searchDb(self::$db, $data);
                if(!empty($search) || $search != null):
                    $tempResult['other'] = $search;
                endif;

                // Fetch post
                $data = [
                    "id" => $val['post'],
                    "1" => "1",
                    "needle" => "*",
                    "table" => "post"
                ];
    
                $search = Func::searchDb(self::$db, $data);
                if(!empty($search) || $search != null):
                    $tempResult['post'] = $search;
                endif;

                $tempResult['feature'] = $val;

                array_push($result['content'], $tempResult);
            endforeach;

            $this->content = $result;

            return $this->deliver();
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
                "1" => "1",
                "needle" => "user",
                "table" => "post"
            ];

            $search = Func::searchDb(self::$db, $data);
            if(is_int($search)):

                if($search !== $this->user):
                    $other = $search;
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
                                "other",
                                "date",
                                "time"
                            ];

                            $items = [
                                Func::tokenGenerator(),
                                $post,
                                $this->user,
                                $other,
                                Func::dateFormat(),
                                time()
                            ];

                            $inserting = new Insert(self::$db, 'feature', $subject, '');
                            $action = $inserting->push($items, 'siiisi');
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
            endif;

            return $this->deliver();
        }

        public function confirmFeature() : array {

            return $this->deliver();
        }

    }
?>