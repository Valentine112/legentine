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

    class Post extends Response{

        private static $db;

        public function __construct(mysqli $db, array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;
        }

        public function config_data(array|string $blocked, array $items, string $key, ?int $user) : array {

            // Fetch the necessary about post owner
            // Since i would be looping through the post here
            // Then i would fetch the post owner details here

            $result = [];

            $box = [
                "post" => [],
                "other" => [],
                "self" => [
                    "user" => $user
                ],
                "more" => []
            ];

            $blocked = $blocked[0];

            foreach ($items as $item):
                // Check if post exist in blocked
                // I created a function to search for needle in an object
                // If 1 is in the array, it means there is a match
                
                $search = Func::searchObject($blocked, $item[$key], "other");

                if(!in_array(1, $search)):
                    
                    // Fetch post owner details
                    $other = $item['user'];
                    $post = $item['id'];

                    $this->selecting->more_details("WHERE id = ? LIMIT 1, $other");
                    $action = $this->selecting->action("fullname, username, photo, rating", "user");
                    $this->selecting->reset();

                    if($action != null):
                        return $action;
                    endif;

                    //print_r($this->selecting->pull());

                    $other_user = $this->selecting->pull()[0][0];

                    // Check if post has been liked by user
                    $this->selecting->more_details("WHERE post = ? AND other = ?, $post, $user");
                    $action = $this->selecting->action("other", "star");
                    $this->selecting->reset();

                    if($action != null):
                        return $action;

                    endif;

                    // validating if the user has liked by checking if the like does exist
                    if($this->selecting->pull()[1] > 0):
                        $box['more']['starred'] = true;

                    else:
                        $box['more']['starred'] = false;

                    endif;

                    // Check if user has saved post
                    $this->selecting->more_details("WHERE post = ? AND user = ?, $post, $user");
                    $action = $this->selecting->action("id", "saved");
                    $this->selecting->reset();
    
                    if($action != null):
                        return $action;
                    endif;
    
                    if($this->selecting->pull()[1] > 0):
                        $box['more']['saved-state'] = 1;
                    else:
                        $box['more']['saved-state'] = 0;
                    endif;

                    // END //

                    // Check if its the same person
                    $box['more']['owner'] = $user === $other ? true : false;

                    // Save the post owner also
                    $box["other"] = $other_user;

                    // Save the post
                    $box["post"] = $item;

                    array_push($result, $box);
                endif;
            endforeach;

            return $result;
        }

        public function check_ownership(string $token, string $item) : array {

            // Check if the post is valid and user is the owner of the post
            $this->selecting->more_details("WHERE token = ? AND user = ?, $token, $this->user");
            $action = $this->selecting->action($item, "post");
            $this->selecting->reset();

            if($action != null):
                return $action;
            endif;

            return $this->selecting->pull();
        }

        public static function fetchId(array $data) : array {
            $token = Func::cleanData($data['token'], 'string');
            $table = Func::cleanData($data['table'], 'string');

            $selecting = new Select(self::$db);

            $selecting->more_details("WHERE token = ? LIMIT 1, $token");
            $action = $selecting->action("id", $table);
            $selecting->reset();

            if($action != null) return $action;

            return $selecting->pull();
        }

        public function create_post() : array {
            unset($this->data['val']['token']);

            $subject = [
                'token',
                'user',
                ...array_keys($this->data['val']),
                'date',
                'time'
            ];

            $items = [
                Func::tokenGenerator(),
                $this->user,
                ...array_values($this->data['val']),
                Func::dateFormat(),
                time()
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

        public function update_post() : array {
            $user = $this->user;

            $token = $this->data['val']['token'];

            $content = $this->data['val']['content'];
            $title = $this->data['val']['title'];
            $category = $this->data['val']['category'];
            $privacy = $this->data['val']['privacy'];


            // If there is a value, then user owns the post
            // Else, it's not his
            $ownership = $this->check_ownership($token, "privacy");

            if($ownership[1] > 0):
                $post_privacy = $ownership[0][0]['privacy'];

                // Checking if the post privacy is 1 and proceed not to change it if it is
                if($post_privacy === 1):
                    $privacy = 1;
                endif;

                $updating = new Update(self::$db, "SET content = ?, title = ?, category = ?, privacy = ? WHERE token = ? AND user = ?# $content# $title# $category# $privacy# $token# $user");
                $action = $updating->mutate('sssisi', 'post');
                if($action):
                    $this->type = "success";
                    $this->status = 1;
                    $this->message = "void";

                else:

                    return $action;
                endif;
            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "void";
                $this->content = "Post does not belong to user";

            endif;

            return $this->deliver();
        }

        public function fetch_post($session) : array {
            (int) $zero = 0;
            $from = $this->data['val']['from'];
            $filter = $this->data['val']['filter'];

            $result = "";
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
                $blocked_users = [];

                // Check where the request is coming from and process
                if($from === "home" || $from == "rank" || $from == "saved"):
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

                endif;

                // Check if there is a filter attached
                if($from != "session"):
                    if($filter === ""):
                        $this->selecting->more_details("WHERE privacy = ? $order, $zero");
                    else:
                        $this->selecting->more_details("WHERE privacy = ? AND category = ? $order, $zero, $filter");
                    endif;
                else:
                    $token = $filter['token'];

                    $this->selecting->more_details("WHERE token = ?, $token");
                endif;

                $action = $this->selecting->action("*", "post");
                $this->selecting->reset();

                if($action != null):
                    return $action;
                endif;

                $post = $this->selecting->pull();

                $result = $this->config_data($blocked_users, $post[0], "user", $user);

            else:
                $result = [];

                $box = [
                    "post" => [],
                    "other" => [],
                    "self" => [
                        "user" => 0
                    ],
                    "more" => []
                ];

                // If user is not logged in
                $this->selecting->more_details("WHERE privacy = ? $order, $zero");
                $action = $this->selecting->action("*", "post");
                $this->selecting->reset();

                if($action != null):
                    return $action;
                endif;

                $value = $this->selecting->pull()[0];
                foreach($value as $val):
                    $box = [
                        "post" => $val,
                        "other" => [],
                        "self" => [
                            "user" => 0
                        ]
                    ];

                    array_push($result, $box);

                endforeach;

            endif;

            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            return $this->deliver();
        }

        public function toggle_comment() : array {

            $token = $this->data['val']['token'];

            // Check if the post is valid and user is the owner of the post
            $ownership = $this->check_ownership($token, "comments_blocked");

            $value = $ownership;
            // If there is a value, then the user is the owner of the post
            if($value[1] > 0):
                if($value[0][0]['comments_blocked'] === 0):
                    $new_value = 1;
                else:
                    $new_value = 0;
                endif;

                $updating = new Update(self::$db, "SET comments_blocked = ? WHERE token = ? AND user = ?# $new_value# $token# $this->user");
                $action = $updating->mutate('isi', 'post');

                if($action):
                    $this->type = "success";
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = $new_value;

                else:

                    return $action;
                endif;
            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "void";
                $this->content = "Post does not belong to user";

            endif;
            
            return $this->deliver();
        }

        public function delete_post() : array {

            // To delete post
            // We have to delete the comments, mentions, notifications and every other thing related to it

            $token = $this->data['val']['token'];

            // Check if the post is valid and user is the owner of the post
            $ownership = $this->check_ownership($token, "id");

            $value = $ownership;

            self::$db->autocommit(false);

            // If there is a value, then the user is the owner of the post
            if($value[1] > 0):
                $post = $value[0][0]['id'];

                // Delete post first
                $deleting = new Delete(self::$db, "WHERE id = ? AND user = ?, $post, $this->user");
                $action = $deleting->proceed("post");
                if($action):
                    $deleting->end();

                    // Delete comments related to the post
                    $deleting = new Delete(self::$db, "WHERE post = ?, $post");
                    $action = $deleting->proceed("comments");
                    if($action):
                        self::$db->autocommit(true);

                        // If everything i set, commit to true and send a positive response
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = "Post deleted";

                    else:
                        return $action;

                    endif;

                else:
                    return $action;

                endif;


            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "void";
                $this->content = "Post does not belong to user";

            endif;
            
            return $this->deliver();
        }

        public function save_post() : array {
            // Check if post belongs to user
            // If it doesn't, proceed to save the post

            $token = $this->data['val']['token'];

            // Check if post belongs to user and if it actually exists
            
            $ownership = $this->check_ownership($token, "id");

            // If the post exist, then the post belongs to the user
            // And he cannot save that
            if($ownership[1] < 1):
                // Fetch post id
                $data = [
                    'token' => $token,
                    'table' => "post"
                ];

                // Get post id
                $post = $this->fetchId($data)[0][0]['id'];

                // Check if post has already been saved
                $this->selecting->more_details("WHERE post = ? AND user = ?, $post, $this->user");
                $action = $this->selecting->action("id", "saved");
                $this->selecting->reset();

                if($action != null):
                    return $action;
                endif;

                // Post has been saved before so proceed to remove
                if($this->selecting->pull()[1] > 0):
                    $saved = $this->selecting->pull()[0][0]['id'];

                    $deleting = new Delete(self::$db, "WHERE id = ?, $saved");
                    $action = $deleting->proceed("saved");

                    if($action):
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "fill";
                        $this->content = "Post Unsaved";

                    else:
                        return $action;
                    endif;
                else:
                    // Since post has not been saved
                    // And this action was activated, proceed to save post
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

                    $inserting = new Insert(self::$db, "saved", $subject, "");
                    $action = $inserting->push($items, 'siisi');
                    $inserting->reset();

                    if($action):
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "fill";
                        $this->content = "Post Saved";
                    
                    else:
                        return $action;

                    endif;
                endif;

            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "void";
                $this->content = "Post belongs to user";

            endif;

            return $this->deliver();
        }

    }
    

?>