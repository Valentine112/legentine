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

    use Model\{
        Comment,
        Feature
    };

    class Post extends Response{

        private static $db;

        public function __construct(mysqli $db, ?array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;

            return $this;
        }

        public function config_data(array|string $from, array $items, string $key, ?int $user) : array {

            /**
             * An empty $blocked string would always be passed
             * The sorting based on blocked users would be done directly in the sql query
             * And not this way, this process reduces the number of data
             */

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

            foreach ($items as $item):
                    
                // Fetch post owner details
                $other = $item['user'];
                $post = $item['id'];

                $this->selecting->more_details("WHERE id = ? LIMIT 1# $other");
                $action = $this->selecting->action("fullname, username, photo, rating", "user");
                $this->selecting->reset();

                if($action != null):
                    return $action;
                endif;

                $other_user = $this->selecting->pull()[0][0];

                // Check if post has been liked by user
                $this->selecting->more_details("WHERE post = ? AND user = ?# $post# $user");
                $action = $this->selecting->action("user", "star");
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

                // END //


                // Check if user has saved post
                $this->selecting->more_details("WHERE post = ? AND user = ?# $post# $user");
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

                // Check feature request status
                $data = [
                    "user" => $user,
                    "post" => $post,
                    "needle" => "*",
                    "table" => "feature"
                ];

                $search = Func::searchDb(self::$db, $data, "AND");

                if(isset($search['status'])):
                    $box['more']['feature'] = $search;
                else:
                    // User hasn't sent a feature request
                    $box['more']['feature'] = -1;
                endif;

                // END //

                // If page is from Saved, get the saved token
                if($from === "saved"):
                    $data = [
                        "user" => $user,
                        "post" => $post,
                        "needle" => "token",
                        "table" => "saved"
                    ];

                    $savedToken = Func::searchDb(self::$db, $data, "AND");
                    $box['more']['savedToken'] = $savedToken;

                endif;


                // Check if its the same person
                $box['more']['owner'] = $user === $other ? true : false;

                // Save the post owner also
                $box["other"] = $other_user;

                // Save the post
                $box["post"] = $item;

                array_push($result, $box);
            endforeach;

            return $result;
        }

        public function check_ownership(string $token, string $item) : array {

            // Check if the post is valid and user is the owner of the post
            $this->selecting->more_details("WHERE token = ? AND user = ?# $token# $this->user");
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

            $selecting->more_details("WHERE token = ? LIMIT 1# $token");
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
                $action = $inserting->push($items, 'sisssisi');
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
            $week = ((60 * 60) * 24) * 7;

            $from = $this->data['val']['from'];
            $filter = $this->data['val']['filter'];

            (array) $result = [];
            (array) $comments = [];
            (string) $order = "";
            (int) $user = 0;
            (int) $zero = 0;

            // Determine the order based on the page the request was sent from
            if($from === "rank"):
                $order = "ORDER BY stars DESC LIMIT 15";
            else:
                $order = "ORDER BY id DESC LIMIT 2";
            endif;

            // Verify that user is logged in
            if($session['type'] === 2):
                $user = $session['content'];

                // Check where the request is coming from and process
                if($from === "home" || $from == "rank"):
                    /**
                     * For homepage
                     * Every blocked user wouldn't be displayed
                     * This would only work here
                     */

                    $blocked_query = Func::blockedUsers(self::$db, $user)[0];$blocked_result = Func::blockedUsers(self::$db, $user)[1];
                endif;
                /**
                 * Fetching post from pages that requires the user to be logged in
                 * Pages ---- Session
                 *  Pages ---- Profile
                 * Pages ---- Saved
                 * Pages ---- Private
                 */

                if($from == "session"):
                    $token = $filter['token'];

                    $this->selecting->more_details("WHERE token = ?# $token");
                endif;

                if($from === "profile"):
                    $more = $this->data['val']['more'];
    
                    // Profile of the user
                    // There would be no token in the url, so i use the one gotten from the cookie
                    if($more === "") $more = $this->user;

                    // Configuring the data to be able to fetch depending on the pages its been pulled from

                    $query = "";
                    $queryParam = "";

                    // If isset 'new', this means that the data is been sent from moreData
                    // If so, we modify the data

                    if(isset($this->data['val']['new'])):
                        $query = $this->data['val']['query'];
                        $queryParam = "# ".$this->data['val']['value'];

                    endif;
                    

                    if($filter === "notes"):
                        $this->selecting->more_details("WHERE privacy = ? AND user = ? $query $order# $zero# $more"."$queryParam");

                    endif;

                endif;

                if($from === "saved"):
                    // Configuring the data to be able to fetch depending on the pages its been pulled from

                    $query = "";
                    $queryParam = "";

                    // If isset 'new', this means that the data is been sent from moreData
                    // If so, we modify the data

                    if(isset($this->data['val']['new'])):
                        $query = $this->data['val']['query'];
                        $queryParam = "# ".$this->data['val']['value'];
                    endif;

                    // First fetch all the post from the saved pointing to this user
                    $this->selecting->more_details("WHERE user = ? $query $order# $this->user"."$queryParam");
                    $action = $this->selecting->action('*', 'saved');
                    $this->selecting->reset();

                    if($action != null):
                        return $action;
                    endif;

                    $savedPost = $this->selecting->pull();

                    $tempResult = [
                        0 => [],
                        1 => 0
                    ];

                    foreach($savedPost[0] as $ind => $saved):
                        // Fetch the post that has been saved by this user
                        $post = $saved['post'];

                        $this->selecting->more_details("WHERE id = ?# $post");
                        $action = $this->selecting->action('*', 'post');
                        $this->selecting->reset();

                        if($action != null):
                            return $action;
                        endif;

                        $tempResult[1] = $ind + 1;

                        // This avoids the case of throwing an error when the post is deleted
                        if($this->selecting->pull()[1] > 0):
                            array_push($tempResult[0], $this->selecting->pull()[0][0]);

                        endif;

                    endforeach;

                    $result = $this->config_data($from, $tempResult[0], "user", $user);

                    $this->type = "success";
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = $result;
        
                    return $this->deliver();
                endif;

            else:
                // If user is not logged in
                $this->selecting->more_details("WHERE privacy = ? $order# $zero");

            endif;

            // FETCHING POST BASED ON THE TYPE OF PAGE THE USER IS IN

            // Check if there is a filter attached
            // Fetch the post in regards to the page
            /**
             * User can choose not to be logged in on any of this page
             * Pages ---- "home"
             * Pages ---- "rank"
             * Pages ---- "read"
             */
            
            if($from == "home"):
                // Configuring the data to be able to fetch depending on the pages its been pulled from

                $query = "";
                $queryParam = "";

                // If isset 'new', this means that the data is been sent from moreData
                // If so, we modify the data

                if(isset($this->data['val']['new'])):
                    $query = $this->data['val']['query'];
                    $queryParam = "# ".$this->data['val']['value'];
                    $filter = $this->data['val']['filter'];

                endif;

                if($filter === ""):
                    $this->selecting->more_details("WHERE privacy = ? $blocked_query  $query $order# $zero# $blocked_result"."$queryParam");

                else:
                    $this->selecting->more_details("WHERE privacy = ? AND category = ? $blocked_query $query $order# $zero# $filter# $blocked_result"."$queryParam");

                endif;

            elseif($from == "rank"):
                // In this section the time range would also be put into account
                // If it's weekly then the post would be based on under one week
                // Else, it would be all time
                $time_range = 0;

                $time = $this->data['val']['more'];

                // If all time, then make the time_range the present time, so when proceed, the range would be 0
                // If it's weekly, then the time range should be one week back

                if($time == "all-time") {
                    $time_range = time();

                }elseif($time == "weekly") {
                    $time_range = $week;
                }

                // Processing the time for the query
                $range = time() - $time_range;

                if($filter === ""):
                    $this->selecting->more_details("WHERE privacy = ? AND time >= ? AND stars > ? $blocked_query $order# $zero# $range# $zero# $blocked_result");

                else:
                    $this->selecting->more_details("WHERE privacy = ? AND category = ? AND time >= ? AND stars > ? $blocked_query $order# $zero# $filter# $range# $zero# $blocked_result");

                endif;

            elseif($from == "read"):
                $token = $filter['token'];

                $this->selecting->more_details("WHERE token = ?# $token");

                // Fetching the comments for the post
                $data = [
                    'token'=> $token,
                    'table' => 'post'
                ];
    
                $post = 0;
    
                $post = $this->fetchId($data)[0][0]['id'];

                $fetch_comments = new Comment(self::$db, null, $this->user);
                $fetch_comments = $fetch_comments->fetch_comment($post);

                if($fetch_comments['status'] === 1):
                    $comments = $fetch_comments['content'];
 
                else:
                    return $fetch_comments;
                endif;

                // END //


                // Fetch the features for the post
                $fetchFeature = new Feature(self::$db, null, $this->user);
                $fetchFeature = $fetchFeature->fetchRequest($post);

                if($fetchFeature['status'] === 1):
                    $feature = $fetchFeature['content'];
 
                else:
                    return $fetchFeature;
                endif;

                // END //

            endif;

            $action = $this->selecting->action("*", "post");
            $this->selecting->reset();

            if($action != null) return $action;

            $post = $this->selecting->pull();


            $result = $this->config_data([], $post[0], "user", $user);

            if($from === "read"):
                $result[0]['comments'] = $comments;
                $result[0]['feature'] = $feature;
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

                $data = [
                    "post" => $post,
                    "user" => $this->user,
                    "needle" => "id",
                    "table" => "saved"
                ];

                // Check if post has been saved before
                $search = Func::searchDb(self::$db, $data, "AND");

                // Post has been saved before so proceed to remove
                if(is_int($search)):
                    $saved = $search;

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

        public function remove_saved_post() : array {

            $token = $this->data['val']['token'];

            // Confirm if the post exist by searching for its id via its token
            $data = [
                "token" => $token,
                "1" => "1",
                "needle" => "id",
                "table" => "post"
            ];

            $search = Func::searchDb(self::$db, $data, "AND");

            if(is_int($search)):
                // Delete the post from saved table
                // Use the post id and the user id
                // This works since a user can only save a particular post once

                $deleting = new Delete(self::$db, "WHERE post = ? AND user = ?, $search, $this->user");
                $action = $deleting->proceed('saved');
                if($action):

                    // If everything i set, commit to true and send a positive response
                    $this->type = "success";
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = "Post deleted";

                else:
                    return $action;

                endif;

            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "fill";
                $this->content = "Post does not exist";

            endif;


            return $this->deliver();
        }

        public function react() : array {
            $token = $this->data['val']['token'];
            $status = -1;
            
            $data = [
                'token' => $token,
                'table' => 'post'
            ];

            $value = $this->fetchId($data);
            if($value[1] > 0):
                $post = $value[0][0]['id'];

                // Fetch post owner id
                $data = [
                    "id" => $post,
                    "1" => "1",
                    "needle" => "user",
                    "table" => "post"
                ];

                $other = Func::searchDb(self::$db, $data, "AND");

                // Check if its already been liked
                $data = [
                    "post" => $post,
                    "user" => $this->user,
                    "needle" => "id",
                    "table" => "star"
                ];

                $search = Func::searchDb(self::$db, $data, "AND");

                // Turn off the database until every transaction is completed
                self::$db->autocommit(false);

                if(is_int($search)):

                    // Reaction exist, so delete 
                    $star = $search;

                    $deleting = new Delete(self::$db, "WHERE id = ?, $star");
                    $action = $deleting->proceed("star");
                    if($action):
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = [
                            "type" => "unstar",
                            "count" => 0
                        ];

                        $status = 0;
                    
                    else:
                        return $action;

                    endif;

                else:

                    // Reaction doesn't exist so create a new one
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

                    $inserting = new Insert(self::$db, "star", $subject, "");
                    $action = $inserting->push($items, 'siisi');
                    $inserting->reset();

                    if($action):
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = [
                            "type" => "star",
                            "count" => 0
                        ];

                        $status = 1;
                        
                    else:
                        return $action;
                    endif;

                endif;

                // Fetch the total number of likes here
                // Sum it up and save it in the post table also
                // Then send it back as a value for count key in content

                // Get the total number of likes
                $this->selecting->more_details("WHERE post = ?# $post");
                $action = $this->selecting->action("id", "star");
                $this->selecting->reset();

                if($action != null){
                    return $action;
                }
                $value = $this->selecting->pull();

                $total = $value[1];

                // Update the likes in the post table
                $updating = new Update(self::$db, "SET stars = ? WHERE id = ?# $total# $post");
                $action = $updating->mutate('ii', 'post');
                if($action):
                    // Turn the database on since every transaction has been completed
                    self::$db->autocommit(true);

                    $this->content['count'] = $total;
                endif;
            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "fill";
                $this->content = "Post must have been deleted";

            endif;

            return $this->deliver();
        }

        public function reader() : array {
            (int) $one = 1;
            $post = $this->data['val']['post'];


            // Fetching the comments for the post
            $data = [
                'token'=> $post,
                'table' => 'post'
            ];

            $post = $this->fetchId($data)[0][0]['id'];

            $data = [
                'post' => $post,
                'user' => $this->user,
                'needle' => 'id',
                'table' => 'readers' 
            ];

            // Check if user already read post
            $search = Func::searchDb(self::$db, $data, "AND");

            if(!$search):

                // Save as new reader
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

                self::$db->autocommit(false);

                $inserting = new Insert(self::$db, "readers", $subject, "");
                $action = $inserting->push($items, 'siisi');
                if($action):
                    // Update readers on post
                    $updating = new Update(self::$db, "SET readers = readers + ? WHERE id = ?# $one# $post");
                    $action = $updating->mutate('ii', 'post');
                    if($action):
                        self::$db->autocommit(true);
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = "New reader added successfully";

                    else:
                        return $action;
                    endif;
                else:
                    return $action;
                endif;
            endif;

            return $this->deliver();
        }

    }
    

?>