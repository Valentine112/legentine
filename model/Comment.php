<?php
    namespace Model;

    use mysqli;
    use Query\{
        Insert,
        Select,
        Update,
        Delete
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

        public function check_permission(string $token, string $post) : array {
            $authority = [
                "comment" => false,
                "post" => false,
            ];

            $this->selecting->more_details("WHERE token = ? AND user = ?, $token, $this->user");
            $action = $this->selecting->action("id", "comments");
            $this->selecting->reset();

            if($action != null) return $action;
            $value = $this->selecting->pull();

            // Check if user is the owner of the post
            $this->selecting->more_details("WHERE token = ? AND user = ?, $post, $this->user");
            $action = $this->selecting->action("id", "post");
            $this->selecting->reset();

            if($action != null) return $action;
            $value1 = $this->selecting->pull();

            if($value[1]):
                $authority['comment'] = $value[0][0]['id'];

            elseif($value1[1] > 0):
                $authority['post'] = $value1[0][0]['id'];

            endif;

            return $authority;

        }

        public function fetch_comment($post) : array {
            $result = [];

            $this->selecting->more_details("WHERE post = ? LIMIT 20, $post");
            $action = $this->selecting->action("*", "comments");
            $this->selecting->reset();

            if($action != null) return $action;

            $value = $this->selecting->pull();


            // Fetch the comment owner details
            foreach($value[0] as $val):
                $other = $val['user'];

                //Fetching the comment owner details
                $this->selecting->more_details("WHERE id = ?, $other");
                $action = $this->selecting->action("fullname, username, photo, rating", "user");
                $this->selecting->reset();

                if($action != null) return $action;

                $other_val = $this->selecting->pull()[0][0];

                // Fetch the post owner id
                $this->selecting->more_details("WHERE id = ? LIMIT 1, $post");
                $action = $this->selecting->action("user", "post");
                $this->selecting->reset();

                if($action != null):
                    return $action;
                endif;

                $post_owner = $this->selecting->pull()[0][0]['user'];

                $arr = [
                    "comment" => $val,
                    "other" => $other_val,
                    "self" => $this->user,
                    "more" => [
                        "post_owner" => $post_owner
                    ]
                ];

                array_push($result, $arr);

            endforeach;

            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            return $this->deliver();
        }

        public function create_comment() : array {
            $val = $this->data['val'];

            (int) $one = 1;
            (array) $result = [];
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

                // Turn off the database
                self::$db->autocommit(false);

                $inserting = new Insert(self::$db, "comments", $subject, "");
                $action = $inserting->push($items, 'siissi');
                if($action):

                    // Update the number of comments in post
                    $updating = new Update(self::$db, "SET comments = comments + ? WHERE id = ?# $one# $post");
                    $action = $updating->mutate('ii', 'post');
                    if($action):
                        // Fetch the comment owner info
                        $this->selecting->more_details("WHERE id = ? LIMIT 1, $this->user");
                        $action = $this->selecting->action("fullname, username, photo, rating", "user");
                        $this->selecting->reset();

                        if($action != null):
                            return $action;
                        endif;

                        $other_user = $this->selecting->pull()[0][0];

                        // Fetch the post owner id
                        $this->selecting->more_details("WHERE id = ? LIMIT 1, $post");
                        $action = $this->selecting->action("user", "post");
                        $this->selecting->reset();

                        if($action != null):
                            return $action;
                        endif;

                        $post_owner = $this->selecting->pull()[0][0]['user'];

                        $result['comment'] = array_combine($subject, $items);
                        $result['other'] = $other_user;
                        $result['self'] = $this->user;
                        $result['more'] = [
                            'post_owner' => $post_owner
                        ];

                        // Commit changes to the database
                        self::$db->autocommit(true);

                        $this->status = 1;
                        $this->message = "void";
                        $this->content = [
                            "comment" => $result
                        ];

                    else:
                        return $action;
                    endif;

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

        public function edit_comment() : array {
            $val = $this->data['val'];

            $post = $val['post'];
            $comment = $val['comment'];

            // Save the comment token with a new variable to send back to the client
            $token = $comment;

            (bool) $authority = false;
            (int) $one = 1;

            $result = [];

            $permission = $this->check_permission($comment, $post);

            // Check if user is the owner of the commment
            if(is_int($permission["comment"])):
                $authority = true;

            endif;

            // Validate if the user has authority to edit the comment
            if($authority):
                $comment = $permission['comment'];

                $comment_value = $val['comment_value'];
                if(strlen(trim($comment_value)) > 0):

                    // Edit the comment
                    $updating = new Update(self::$db, "SET comment = ?, status = ? WHERE id = ?# $comment_value# $one# $comment");
                    $action = $updating->mutate('sii', 'comments');

                    if($action):
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = [
                            "token" => $token,
                            "comment" => $comment_value
                        ];

                    else:
                        return $action;
                    endif;
                else:
                    $this->type = "warning";
                    $this->status = 0;
                    $this->message = "fill";
                    $this->content = "Comment is empty";

                endif;
            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "void";
                $this->content = "You do not have authority to perform this action";

            endif;

            return $this->deliver();
        }

        public function delete_comment() : array {
            (int) $one = 1;
            $authority = false;

            $val = $this->data['val'];

            $post = $val['post'];
            $comment = $val['token'];

            $permission = $this->check_permission($comment, $post);

            // Check if user is the owner of the commment
            if(is_int($permission["comment"])):
                $authority = true;
            
            elseif(is_int($permission['post'])):
                $authority = true;

            endif;

            // Validate if the user has authority to delete the comment
            if($authority):
                $comment = $permission['comment'];

                self::$db->autocommit(false);
                // Delete comment
                $deleting = new Delete(self::$db, "WHERE id = ?, $comment");
                $action = $deleting->proceed("comments");

                if($action):

                    // Update the number of comments in post
                    $updating = new Update(self::$db, "SET comments = comments - ? WHERE token = ?# $one# $post");
                    if($action = $updating->mutate('ii', 'post')):
                        // Delete mentions and also notifications regarding this comment and mention
                        self::$db->autocommit(true);

                        // If everything i set, commit to true and send a positive response
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = "Comment deleted";
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
                $this->content = "You do not have authority to perform this action";

            endif;

            return $this->deliver();
        }
    }
?>