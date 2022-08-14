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

        public function check_permission(string $comment, string $post) : array {
            $authority = [
                "comment" => false,
                "post" => false,
            ];

            // Check if user is the owner of the comment
            $data = [
                "token" => $comment,
                "user" => $this->user,
                "needle" => "id",
                "table" => "comments"
            ];

            $search = Func::searchDb(self::$db, $data);

            // Check if user is the owner of the post
            $data['token'] = $post;
            $data['table'] = "post";

            $search1 = Func::searchDb(self::$db, $data);

            if(is_int($search)):
                $authority['comment'] = $search;

            elseif(is_int($search1)):
                $authority['post'] = $search1;

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

                
                $val['content'] = Func::mention(self::$db, $val['content'], ['comment' => $val['id']]);

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
            // The new value must be passed through a function that check wether a mention is present
            // If none is present, it returns the original value
            // Else it returns a modified one that fits the mention standard
            
            $val = $this->data['val'];

            (int) $one = 1;
            (array) $result = [];

            // Fetch post first
            $postClass = new Post(self::$db, null, "");
            $item = [
                "token" => $val['filter'],
                "table" => "post"
            ];

            $post = $postClass->fetchId($item)[0][0]['id'];

            if(strlen(trim($val['content'])) > 0):
                $token = Func::tokenGenerator();
                $subject = [
                    "token",
                    "post",
                    "user",
                    "content",
                    "date",
                    "time"
                ];

                $items = [
                    $token,
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
                $inserting->reset();
                if($action):
                    
                    // Fetch the comment id
                    // Would need it for storing any mention
                    $item = [
                        "token" => $token,
                        "table" => "comments"
                    ];

                    $comment = $postClass->fetchId($item)[0][0]['id'];

                    // Insert mentions if there are any
                    $mentions = $val['mentions'];
                    foreach($mentions as $mention):

                        $this->selecting->more_details("WHERE username = ?, $mention");
                        $action = $this->selecting->action("id", "user");
                        $this->selecting->reset();

                        if($action != null) return $action;

                        $other = $this->selecting->pull();
                        if($other[1] > 0):
                            $other = $other[0][0]['id'];

                            $subject1 = [
                                "token",
                                "post",
                                "comment",
                                "user",
                                "other",
                                "type",
                                "date",
                                "time"
                            ];
                            
                            $items1 = [
                                Func::tokenGenerator(),
                                $post,
                                $comment,
                                $this->user,
                                $other,
                                "comment",
                                Func::dateFormat(),
                                time()
                            ];

                            $inserting = new Insert(self::$db, "mentions", $subject1, "");
                            $action = $inserting->push($items1, "siiiissi");
                            $inserting->reset();

                            if(!$action) return $action;
                        endif;

                    endforeach;

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

                        $items[3] = Func::mention(self::$db, $items[3], ['comment' => $comment]);

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
            // The new value must be passed through a function that check wether a mention is present
            // If none is present, it returns the original value
            // Else it returns a modified one that fits the mention standard

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

                    self::$db->autocommit(false);
                    // Edit the comment
                    $updating = new Update(self::$db, "SET content = ?, edited = ? WHERE id = ?# $comment_value# $one# $comment");
                    $action = $updating->mutate('sii', 'comments');

                    if($action):
                        // Working on the mentions here
                        $mentions = $val['mentions'];

                        // Remove mentions that are no longer present in this edited reply
                        $removed_mention = $this->removed_mention($mentions, ["comment" => $comment]);

                        if($removed_mention):
                            // Check if the users are valid and fetch their id
                            foreach($mentions as $mention):
    
                                $this->selecting->more_details("WHERE username = ?, $mention");
                                $action = $this->selecting->action("id", "user");
                                $this->selecting->reset();

                                if($action != null) return $action;
    
                                if($this->selecting->pull()[1] > 0):
                                    $other = $this->selecting->pull()[0][0]['id'];

                                    // Check if mention does not exist to determine wether to add one
                                    $data = [
                                        "comment" => $comment,
                                        "other" => $other,
                                        "needle" => "id",
                                        "table" => "mentions"
                                    ];
                        
                                    $search = Func::searchDb(self::$db, $data);
    
                                    if(!$search):
                                        // If it doesn't exist, proceed to add this one
                                        $comment_str = "comment";
                                        $subject1 = [
                                            "token",
                                            "post",
                                            "comment",
                                            "user",
                                            "other",
                                            "type",
                                            "date",
                                            "time"
                                        ];
                                        
                                        $items1 = [
                                            Func::tokenGenerator(),
                                            $post,
                                            $comment,
                                            $this->user,
                                            $other,
                                            $comment_str,
                                            Func::dateFormat(),
                                            time()
                                        ];

                                        $inserting = new Insert(self::$db, "mentions", $subject1, "");
                                        $action = $inserting->push($items1, "siiiissi");

                                        if($action):
                                            // Fetch replies
    
                                        else:
                                            return $action;
                                        endif;
    
                                    endif;
                                else:
                                    $this->content = "Mentioned person does not exist";
                                endif;
                                
                            endforeach;

                            self::$db->autocommit(true);
                        else:
                            $this->content = $removed_mention;
    
                        endif;

                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = [
                            "token" => $token,
                            "comment" => Func::mention(self::$db, $comment_value, ['comment' => $comment])
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
            
            // If not, check if he is the owner of the post
            elseif(is_int($permission['post'])):
                // If this situation, then fetch the comment id
                $data = [
                    "token" => $comment,
                    1 => 1,
                    "needle" => "id",
                    "table" => "comments"
                ];
                $search = Func::searchDb(self::$db, $data);
                if(is_int($search)) $permission['comment'] = $search;

                $authority = true;

            endif;

            // Validate if the user has authority to delete the comment
            if($authority):
                $comment = $permission['comment'];

                self::$db->autocommit(false);
                // Delete comment
                $deleting = new Delete(self::$db, "WHERE id = ?, $comment");
                $action = $deleting->proceed("comments");
                $deleting->end();
                if($action):

                    // Update the number of comments in post
                    $updating = new Update(self::$db, "SET comments = comments - ? WHERE token = ?# $one# $post");
                    if($updating->mutate('ii', 'post')):
                        // Delete mentions and also notifications regarding this comment and mention

                        $deleting = new Delete(self::$db, "WHERE comment = ?, $comment");
                        // Delete replies related to the comment
                        if($deleting->proceed("replies")):
                            $deleting->end();
                            // Delete mentions related to the comment
                            if($deleting->proceed("mentions")):
                                $deleting->end();
                                // Commit the database to true if everything is set
                                self::$db->autocommit(true);

                                // If everything i set, commit to true and send a positive response
                                $this->type = "success";
                                $this->status = 1;
                                $this->message = "void";
                                $this->content = $val['token'];

                            endif;
                        endif;
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

        public function create_reply() : array {
            $val = $this->data['val'];

            (int) $one = 1;
            (array) $result = [];
            // Fetch post first
            $post = new Post(self::$db, null, "");
            $item = [
                "token" => $val['post'],
                "table" => "post"
            ];

            $post = $post->fetchId($item)[0][0]['id'];

            $item = [
                "token" => $val['comment'],
                "table" => "comment"
            ];

            $comment = $post->fetchId($item)[0][0]['id'];

            if(strlen(trim($val['content'])) > 0):

                $subject = [
                    "token",
                    "post",
                    "comment",
                    "user",
                    "content",
                    "date",
                    "time"
                ];

                $items = [
                    Func::tokenGenerator(),
                    $post,
                    $comment,
                    $this->user,
                    $val['content'],
                    Func::dateFormat(),
                    time()
                ];

                self::$db->autocommit(false);

                $inserting = new Insert(self::$db, "replies", $subject, "");
                $action = $inserting->push($items, 'siiissi');
                if($action):
                    // Update the number of replies in post
                    $updating = new Update(self::$db, "SET replies = replies + ? WHERE id = ?# $one# $comment");
                    $action = $updating->mutate('ii', 'comments');
                    if($action):

                        // Fetch the replies owner info
                        $this->selecting->more_details("WHERE id = ? LIMIT 1, $this->user");
                        $action = $this->selecting->action("fullname, username, photo, rating", "user");
                        $this->selecting->reset();

                        if($action != null):
                            return $action;
                        endif;

                        $reply_owner = $this->selecting->pull()[0][0];

                        // Fetch the post owner id
                        $this->selecting->more_details("WHERE id = ? LIMIT 1, $post");
                        $action = $this->selecting->action("user", "post");
                        $this->selecting->reset();

                        if($action != null):
                            return $action;
                        endif;

                        $post_owner = $this->selecting->pull()[0][0]['user'];

                        $result['comment'] = array_combine($subject, $items);
                        $result['other'] = $reply_owner;
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

            endif;

            
            return $this->deliver();
        }

        public function fetch_reply() : array {
            $val = $this->data['val'];

            $item = [
                "token" => $val['comment'],
                "table" => "comment"
            ];

            // Fetch post first
            $post = new Post(self::$db, null, "");

            $comment = $post->fetchId($item)[0][0]['id'];

            $this->selecting->more_details("WHERE comment = ? LIMIT 20, $comment");
            $action = $this->selecting->action("*", "replies");

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

                $post = $val['post'];

                // Fetch the post owner id
                $this->selecting->more_details("WHERE id = ? LIMIT 1, $post");
                $action = $this->selecting->action("user", "post");
                $this->selecting->reset();

                if($action != null) return $action;

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

        public function reply_reply() : array {

            return $this->deliver();
        }

        public function delete_reply() : array {
            /**
             * We need to check for 2 things
             * Check if the person trying to perform this action is the owner of the post
             * Or the person performing this action is the owner of the reply
             * Those are the 2 who have permission to do it
             */
            $authority = 0;
            $val = $this->data['val'];

            $this->type = "error";
            $this->status = 0;
            $this->message = "void";
            $this->content = "Doesn't have permission";

            // Check if its reply owner
            $data = [
                "token" => $val['reply'],
                "user" => $this->user,
                "needle" => "id",
                "table" => "replies"
            ];

            // Check if user is the owner of the reply
            $search = Func::searchDb(self::$db, $data);

            // Check if user is the owner of the post
            $data['token'] = $val['post'];
            $data['table'] = "post";

            $search1 = Func::searchDb(self::$db, $data);

            // It exists, meaning the user is the owner of the reply
            if(is_int($search)):
                $authority = 1;
                $reply = $search;

            elseif(is_int($search1)):
                $authority = 1;

                $reply = $val['reply'];
                // Fetch the reply id
                $this->selecting->more_details("WHERE token = ?, $reply");
                $action = $this->selecting->action("id", "replies");
                $this->selecting->reset();

                if($action != null) return $action;

                $reply = $this->selecting->pull()[0][0]['id'];

            endif;

            if($authority === 1):

                // Delete reply
                // Delete mentions involving reply

                self::$db->autocommit(false);

                $deleting = new Delete(self::$db, "WHERE id = ?, $reply");
                $action = $deleting->proceed("replies");
                if($action):

                    // Delete mentions
                    $from = "reply";
                    $deleting = new Delete(self::$db, "WHERE reply = ? AND from = ?, $reply, $from");
                    $action = $deleting->proceed("mentions");
                    if($action):
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = "Success";

                        self::$db->autocommit(true);
                    else:
                        return $action;
                    endif;
                else:
                    return $action;
                endif;
            endif;
            
            return $this->deliver();
        }

        public function edit_reply() : array {
            // ONLY THE USER WHO CREATED THE REPLY HAS THE PERMISSION TO EDIT IT

            /**
             * Get the new value
             * Get all the mentions also
             * Turn of the database first
             * Check if user has permission to update the reply
             * Update it
             * Check if the mentions are valid users
             * Check if some mentions from the database doesn't exist in the edited reply and proceed to delete them
             * If there is an extra, add it to the mention table, but make sure to check if it was already added to avoid inconsistency
             * The mentions would be stored with the user id, Incase the user changes his/her username, it would affect it
             */

            $val = $this->data['val'];

            $reply = $val['reply'];
            $content = $val['content'];
            (array) $mentions = $val['mention'];

            $this->type = "error";
            $this->status = 0;
            $this->message = "void";
            $this->content = "Doesn't have permission";

            // Turn off the database
            self::$db->autocommit(false);

            // Check if its reply owner
            $data = [
                "token" => $reply,
                "user" => $this->user,
                "needle" => "id",
                "table" => "replies"
            ];

            // Check if user is the owner of the reply
            $search = Func::searchDb(self::$db, $data);

            if(is_int($search)):
                $reply = $search;

                // Update the reply if the user has the permission to do so
                $updating = new Update(self::$db, "SET content = ? WHERE id = ?# $content# $reply");
                $action = $updating->mutate('si', 'replies');

                if($action):

                    // Remove mentions that are no longer present in this edited reply
                    $removed_mention = $this->removed_mention($mentions, ["reply" => $reply]);

                    if($removed_mention):
                        // Check if the users are valid and fetch their id
                        foreach($mentions as $mention):

                            $this->selecting->more_details("WHERE username = ?, $mentioned");
                            $action = $this->selecting->action("id", "user");

                            if($action != null) return $action;

                            $other = $values[0][0]['id'];
                            if(is_int($other)):
                                // Check if mention does not exist to determine wether to add one

                                $data = [
                                    "reply" => $reply,
                                    "other" => $other,
                                    "needle" => "id",
                                    "table" => "mentions"
                                ];
                    
                                // Check if user is the owner of the reply
                                $search = Func::searchDb(self::$db, $data);

                                if(!$search):
                                    // If it doesn't exist, proceed to add this one
                                    $inserting = new Insert(self::$db, "mentions", $subject, "");
                                    $action = $inserting->push($items, "");
                                    if($action):
                                        // Fetch replies

                                    else:
                                        return $action;
                                    endif;

                                endif;
                            else:
                                $this->content = "Mentioned person does not exist";
                            endif;

                        endforeach;
                    else:
                        $this->content = $removed_mention;

                    endif;
                else:
                    $this->content = "Couldn't update reply";
                endif;
            endif;

            return $this->deliver();
        }

        public function removed_mention(array $mentions, array $data) {
            $key = array_keys($data)[0];
            $val = array_values($data)[0];

            $result = false;
            // Fetch all the mentions in the reply
            // Then check if there is anyone missing from the new mentions
            // If there is, delete that one

            $this->selecting->more_details("WHERE $key = ?, $val");
            $action = $this->selecting->action("other", "mentions");
            $this->selecting->reset();

            if($action != null) return $action;

            $value = $this->selecting->pull()[0];
            foreach($value as $val1):
                $result = true;
                // Get previous mentioned person's username
                $data = [
                    "id" => $val1['other'],
                    "1" => "1",
                    "needle" => "username",
                    "table" => "user" 
                ];

                $search = Func::searchDb(self::$db, $data);
                if(is_string($search)) $mentioned = $search;

                // If the mentioned from the database does not exist with the new mention
                // Delete it
                if(!in_array($mentioned, $mentions)):
                    $other = $val1['other'];
                    // Proceeding to delete the old mention
                    $deleting = new Delete(self::$db, "WHERE $key = ? AND other = ?, $val, $other");
                    $result = $deleting->proceed("mentions");
                endif;

            endforeach;

            return $result;
        }
    }
?>