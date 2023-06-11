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

            $search = Func::searchDb(self::$db, $data, "AND");

            // Check if user is the owner of the post
            $data['token'] = $post;
            $data['table'] = "post";

            $search1 = Func::searchDb(self::$db, $data, "AND");

            if(is_int($search)):
                $authority['comment'] = $search;

            elseif(is_int($search1)):
                $authority['post'] = $search1;

            endif;

            return $authority;

        }

        public function fetch_comment($post, ?array $filter) : array {
            $result = [];

            $filterQuery = "";
            $filterValue = "";

            // The checks if the notification are been fetched from controller
            // Or fetch directly from another source
            if($filter !== null):
                $filterQuery = $filter['val']["query"];
                $filterValue = "# ".$filter['val']["value"];

            endif;

            $this->selecting->more_details("WHERE post = ? $filterQuery ORDER BY id DESC LIMIT 20# $post"."$filterValue");
            $action = $this->selecting->action("*", "comments");
            $this->selecting->reset();

            if($action != null) return $action;

            $value = $this->selecting->pull();


            // Fetch the comment owner details
            foreach($value[0] as $val):
                $other = $val['user'];

                //Fetching the comment owner details
                $this->selecting->more_details("WHERE id = ?# $other");
                $action = $this->selecting->action("*", "user");
                $this->selecting->reset();

                if($action != null) return $action;

                $other_val = $this->selecting->pull()[0][0];

                // Fetch the post owner id
                $this->selecting->more_details("WHERE id = ? LIMIT 1# $post");
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
                    "replies",
                    "edited",
                    "date",
                    "time"
                ];

                $items = [
                    $token,
                    $post,
                    $this->user,
                    $val['content'],
                    0,
                    0,
                    Func::dateFormat(),
                    time()
                ];

                // Turn off the database
                self::$db->autocommit(false);

                // Save the comments
                $inserting = new Insert(self::$db, "comments", $subject, "");
                $action = $inserting->push($items, 'siisiisi');
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

                    $arr = [
                        "type" => "comment",
                        "post" => $post,
                        "comment" => $comment,
                        "reply" => 0,
                        "more" => 0
                    ];

                    // Save mentions here
                    $this->save_mentions($mentions, $arr);

                    // Update the number of comments in post
                    $updating = new Update(self::$db, "SET comments = comments + ? WHERE id = ?# $one# $post");
                    $action = $updating->mutate('ii', 'post');
                    if($action):

                        // Fetch the post owner id
                        $data = [
                            "id" => $post,
                            "1" => "1",
                            "needle" => "user",
                            "table" => "post"
                        ];
        
                        $search = Func::searchDb(self::$db, $data, "AND");

                        $post_owner = 0;

                        if(is_int($search)):
                            $post_owner = $search;
                        endif;


                        // Do not save if post owner is coment owner
                        if($post_owner !== $this->user):
                            // Save as comment notification
                            $data = [
                                "element" => $comment,
                                "elementType" => "comment",
                                "user" => $this->user,
                                "other" => $post_owner,
                                "type" => "comment",
                                "parent" => $post
                            ];

                            $save = Notification::saveNotification(self::$db, $data, 'isiissssi');

                            if(!$save) return $save;
                        endif;

                        // Fetch the comment owner info
                        $this->selecting->more_details("WHERE id = ? LIMIT 1# $this->user");
                        $action = $this->selecting->action("fullname, username, photo, rating", "user");
                        $this->selecting->reset();

                        if($action != null):
                            return $action;
                        endif;

                        $other_user = $this->selecting->pull()[0][0];

                        // Sort and arrange the result

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

            $permission = $this->check_permission($comment, $post);

            // Check if user is the owner of the commment
            if(is_int($permission["comment"])):
                $authority = true;

            endif;

            // Fetch post owner
            $data = [
                "token" => $post,
                "1" => "1",
                "needle" => "user",
                "table" => "post"
            ];

            $postOwner = Func::searchDb(self::$db, $data, "AND");

            // Fetch post id
            $data = [
                "token" => $post,
                "1" => "1",
                "needle" => "id",
                "table" => "post"
            ];

            $postId = Func::searchDb(self::$db, $data, "AND");

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
                            $arr = [];

                            foreach($mentions as $mention):
    
                                $this->selecting->more_details("WHERE username = ?# $mention");
                                $action = $this->selecting->action("id", "user");
                                $this->selecting->reset();

                                if($action != null) return $action;
    
                                if($this->selecting->pull()[1] > 0):
                                    array_push($arr, 1);

                                    $other = $this->selecting->pull()[0][0]['id'];

                                    // Check if mention does not exist to determine wether to add one
                                    $data = [
                                        "comment" => $comment,
                                        "other" => $other,
                                        "needle" => "id",
                                        "table" => "mentions"
                                    ];
                        
                                    $search = Func::searchDb(self::$db, $data, "AND");
    
                                    // If it doesn't exist, proceed to add this one
                                    if(!$search):
                                        
                                        // Fetch post id
                                        $data = [
                                            "token" => $post,
                                            "1" => "1",
                                            "needle" => "id",
                                            "table" => "post"
                                        ];

                                        $search = Func::searchDb(self::$db, $data, "AND");

                                        if(is_int($search)) $post = $search;

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


                                        // Making sure that a person cannot mention himself

                                        if($this->user !== $other):

                                            // save the new mention notification here

                                            $data = [
                                                "element" => $comment,
                                                "elementType" => "comment",
                                                "user" => $this->user,
                                                "other" => $other,
                                                "type" => "mention",
                                                "parent" => $post,
                                                "more" => "0"
                                            ];
                    
                                            $save = Notification::saveNotification(self::$db, $data, 'isiisisssi');

                                            if(!$save) return $save;
                                        endif;
                                        // END //

                                        if(!$action):
                                            return $action;
                                        endif;
    
                                    endif;
                                else:
                                    $this->content = "Mentioned person does not exist";

                                endif;
                                
                            endforeach;

                            // There are no mentions or valid mentions
                            // Save the notification as comment rather than mention

                            if(!in_array(1, $arr)):
                                // Making sure that you do not get a notification when you comment under your own post

                                if($postOwner !== $this->user):
                                    // Save as notification
                                    $data = [
                                        "element" => $comment,
                                        "elementType" => "comment",
                                        "user" => $this->user,
                                        "other" => $postOwner,
                                        "type" => "comment",
                                        "parent" => $postId
                                    ];

                                    $save = Notification::saveNotification(self::$db, $data, 'isiissssi');

                                    if(!$save): return $save; endif;
                                endif;

                            else:
                                // Delete the previous comment that was saved as notification
                                $commentStr = "comment";

                                $data = [
                                    "type" => $commentStr,
                                    "element" => $comment,
                                    "elementType" => "comment",
                                    "user" => $this->user 
                                ];

                                $action = Notification::deleteNotification(self::$db, $data);

                                if(!$action) return $action;

                            endif;

                        else:
                            $this->content = $removed_mention;
    
                        endif;

                        self::$db->autocommit(true);

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
                $search = Func::searchDb(self::$db, $data, "AND");
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
                    if($updating->mutate('is', 'post')):
                        // Delete mentions and also notifications regarding this comment and mention

                        $deleting = new Delete(self::$db, "WHERE comment = ?, $comment");
                        // Delete replies related to the comment
                        if($deleting->proceed("replies")):
                            $deleting->end();
                            // Delete mentions related to the comment
                            if($deleting->proceed("mentions")):
                                $deleting->end();

                                // Delete comment notification
                                $data = [
                                    "type" => "comment",
                                    "element" => $comment,
                                    "elementType" => "comment",
                                    "user" => $this->user
                                ];

                                $action = Notification::deleteNotification(self::$db, $data);

                                if($action):
                                    // Delete mention notification from comment
                                    $data['type'] = "mention";

                                    $action = Notification::deleteNotification(self::$db, $data);

                                    if($action):

                                        // Commit the database to true if everything is set
                                        self::$db->autocommit(true);

                                        // If everything i set, commit to true and send a positive response
                                        $this->type = "success";
                                        $this->status = 1;
                                        $this->message = "void";
                                        $this->content = $val['token'];
                                    else:
                                        return $action;
                                    endif;
                                else:
                                    return $action;
                                endif;

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
            $postClass = new Post(self::$db, null, "");

            $item = [
                "token" => $val['post'],
                "table" => "post"
            ];

            // FETCH THE POST ID HERE
            $post = $postClass->fetchId($item)[0][0]['id'];

            $item = [
                "token" => $val['comment'],
                "table" => "comments"
            ];

            // FETCH THE COMMENT ID HERE
            $comment = $postClass->fetchId($item)[0][0]['id'];

            if(strlen(trim($val['content'])) > 0):
                $token = Func::tokenGenerator();

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
                    $token,
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

                    // Fetch the reply id
                    // Would need it for storing any mention
                    $item = [
                        "token" => $token,
                        "table" => "replies"
                    ];

                    $reply = $postClass->fetchId($item)[0][0]['id'];

                    // Insert mentions if there are any
                    $mentions = $val['mentions'];

                    // The "more" key in this object would be used when trying to find the element

                    $arr = [
                        "type" => "reply",
                        "post" => $post,
                        "comment" => $comment,
                        "reply" => $reply,
                        "more" => $comment
                    ];

                    // Save mentions attached to the reply
                    $this->save_mentions($mentions, $arr);

                    // Update the number of replies in comments
                    $updating = new Update(self::$db, "SET replies = replies + ? WHERE id = ?# $one# $comment");
                    $action = $updating->mutate('ii', 'comments');
                    if($action):

                        // Fetch the replies owner info
                        $data = [
                            "id" => $this->user,
                            "1" => "1",
                            "needle" => "*",
                            "table" => "user"
                        ];
            
                        $reply_owner = Func::searchDb(self::$db, $data, "AND");

                        // Fetch the post owner id
                        $data = [
                            "id" => $post,
                            "1" => "1",
                            "needle" => "user",
                            "table" => "post"
                        ];
            
                        $post_owner = Func::searchDb(self::$db, $data, "AND");

                        // Fetch comment owner
                        $data = [
                            "id" => $comment,
                            "1" => "1",
                            "needle" => "user",
                            "table" => "comments"
                        ];
            
                        $commentOwner = Func::searchDb(self::$db, $data, "AND");

                        // Do not save if comment owner is reply owner
                        if($commentOwner !== $this->user):
                            // Save as notification
                            $data = [
                                "element" => $reply,
                                "elementType" => "reply",
                                "user" => $this->user,
                                "other" => $commentOwner,
                                "type" => "reply",
                                "parent" => $post,
                                "more" => $comment
                            ];

                            $save = Notification::saveNotification(self::$db, $data, 'isiisisssi');

                            if(!$save) return $save;
                        endif;

                        // Sorting and arranging the results

                        $items[4] = Func::mention(self::$db, $items[4], ['reply' => $reply]);

                        $result['reply'] = array_combine($subject, $items);
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

        public function fetch_reply(?array $filter) : array {
            $val = $this->data['val'];
            $result = [];

            $filterQuery = "";
            $filterValue = "";

            // The checks if the notification are been fetched from controller
            // Or fetch directly from another source
            if($filter !== null):
                $filterQuery = $filter['val']["query"];
                $filterValue = "# ".$filter['val']["value"];

            endif;

            $item = [
                "token" => $val['comment'],
                "table" => "comments"
            ];

            // Fetch comment using the post fetchId method
            $post = new Post(self::$db, null, "");

            $comment = $post->fetchId($item)[0][0]['id'];

            $this->selecting->more_details("WHERE comment = ? $filterQuery ORDER BY id DESC LIMIT 15# $comment"."$filterValue");
            $action = $this->selecting->action("*", "replies");
            $this->selecting->reset();

            if($action != null) return $action;

            $value = $this->selecting->pull();

            // Fetch the comment owner details
            foreach($value[0] as $val):
                $other = $val['user'];

                //Fetching the comment owner details
                $this->selecting->more_details("WHERE id = ?# $other");
                $action = $this->selecting->action("*", "user");
                $this->selecting->reset();

                if($action != null) return $action;

                $other_val = $this->selecting->pull()[0][0];

                $post = $val['post'];

                // Fetch the post owner id
                $this->selecting->more_details("WHERE id = ? LIMIT 1# $post");
                $action = $this->selecting->action("user", "post");
                $this->selecting->reset();

                if($action != null) return $action;

                $post_owner = $this->selecting->pull()[0][0]['user'];

                // Modify the value
                $val['content'] = Func::mention(self::$db, $val['content'], ['reply' => $val['id']]);

                $arr = [
                    "reply" => $val,
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

        public function delete_reply() : array {
            /**
             * We need to check for 2 things
             * Check if the person trying to perform this action is the owner of the post
             * Or the person performing this action is the owner of the reply
             * Those are the 2 who have permission to do it
             */

            (int) $one = 1;
            $authority = 0;
            $val = $this->data['val'];

            $this->type = "error";
            $this->status = 0;
            $this->message = "void";
            $this->content = "Doesn't have permission";

            // Check if its reply owner
            $data = [
                "token" => $val['token'],
                "user" => $this->user,
                "needle" => "id",
                "table" => "replies"
            ];

            // Check if user is the owner of the reply
            $search = Func::searchDb(self::$db, $data, "AND");

            // Check if user is the owner of the post
            $data['token'] = $val['post'];
            $data['table'] = "post";

            $search1 = Func::searchDb(self::$db, $data, "AND");

            // It exists, meaning the user is the owner of the reply
            if(is_int($search)):
                $authority = 1;
                $reply = $search;

            elseif(is_int($search1)):
                $authority = 1;

                $reply = $val['reply'];

                // Fetch the reply id
                $this->selecting->more_details("WHERE token = ?# $reply");
                $action = $this->selecting->action("id", "replies");
                $this->selecting->reset();

                if($action != null) return $action;

                $reply = $this->selecting->pull()[0][0]['id'];

            endif;

            if($authority === 1):

                // Delete reply
                // Delete mentions involving reply

                // Fetch comment id to update the number of replies it has
                $data = [
                    "id" => $reply,
                    "1" => "1",
                    "needle" => "comment",
                    "table" => "replies"
                ];

                $search = Func::searchDb(self::$db, $data, "AND");

                self::$db->autocommit(false);

                $deleting = new Delete(self::$db, "WHERE id = ?, $reply");
                $action = $deleting->proceed("replies");
                if($action):

                    if(is_int($search)):
                        $comment = $search;

                        // Update the number of replies in comments
                        $updating = new Update(self::$db, "SET replies = replies - ? WHERE id = ?# $one# $comment");
                        $action = $updating->mutate('ii', 'comments');

                        if($action):

                            // Delete mentions
                            $from = "reply";
                            $deleting = new Delete(self::$db, "WHERE reply = ? AND type = ?, $reply, $from");
                            $action = $deleting->proceed("mentions");

                            if($action):

                                // Delete reply notification
                                $data = [
                                    "type" => "reply",
                                    "element" => $reply,
                                    "elementType" => "reply",
                                    "user" => $this->user
                                ];

                                $action = Notification::deleteNotification(self::$db, $data);

                                if($action):
                                    // Delete mention notification from reply
                                    $data['type'] = "mention";

                                    $action = Notification::deleteNotification(self::$db, $data);

                                    if($action):
                                        
                                        $this->type = "success";
                                        $this->status = 1;
                                        $this->message = "void";
                                        $this->content = "Success";

                                        self::$db->autocommit(true);
                                    endif;
                                else:
                                    return $action;
                                endif;
                            else:
                                return $action;
                            endif;
                        else:
                            return $action;
                        endif;
                    else:
                        $this->content = "Couldn't fetch comment id from reply table";
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

            (int) $one = 1;

            $post = $val['post'];
            $reply = $val['reply'];
            $content = $val['content'];
            (array) $mentions = $val['mentions'];

            $reply_token = $reply;

            $this->type = "error";
            $this->status = 0;
            $this->message = "void";
            $this->content = "Doesn't have permission";

            // Turn off the database
            //self::$db->autocommit(false);

            // Check if its reply owner
            $data = [
                "token" => $reply,
                "user" => $this->user,
                "needle" => "id",
                "table" => "replies"
            ];

            // Check if user is the owner of the reply
            $search = Func::searchDb(self::$db, $data, "AND");

            if(is_int($search)):

                $reply = $search;

                // Fetch comment id
                $data = [
                    "id" => $reply,
                    "1" => "1",
                    "needle" => "comment",
                    "table" => "replies"
                ];
                $comment = Func::searchDb(self::$db, $data, "AND");

                if(strlen(trim($content)) > 0):

                    //self::$db->autocommit(false);

                    // Update the reply if the user has the permission to do so
                    $updating = new Update(self::$db, "SET content = ?, edited = ? WHERE id = ?# $content# $one# $reply");
                    $action = $updating->mutate('sii', 'replies');

                    if($action):
                        $arr = [];

                        // Remove mentions that are no longer present in this edited reply
                        $this->removed_mention($mentions, ["reply" => $reply]);

                        // Check if the users are valid and fetch their id
                        foreach($mentions as $mentioned):

                            $data = [
                                "username" => $mentioned,
                                "1" => "1",
                                "needle" => "id",
                                "table" => "user"
                            ];
                
                            $search = Func::searchDb(self::$db, $data, "AND");

                            if(is_int($search)):
                                array_push($arr, 1);

                                $other = $search;

                                /**
                                 * -------------- MENTION ------------
                                 */
                                
                                // Check if mention does not exist to determine wether to add one

                                $data = [
                                    "reply" => $reply,
                                    "other" => $other,
                                    "needle" => "id",
                                    "table" => "mentions"
                                ];
                    
                                $search = Func::searchDb(self::$db, $data, "AND");

                                // If it doesn't exist, proceed to add this one
                                if(!$search):

                                    // Fetch post id
                                    $data = [
                                        "token" => $post,
                                        "1" => "1",
                                        "needle" => "id",
                                        "table" => "post"
                                    ];
                                    
                                    $search = Func::searchDb(self::$db, $data, "AND");

                                    if(is_int($search)) $post = $search;
                                    
                                    $subject = [
                                        "token",
                                        "post",
                                        "reply",
                                        "user",
                                        "other",
                                        "type",
                                        "date",
                                        "time"
                                    ];
                                    
                                    $items = [
                                        Func::tokenGenerator(),
                                        $post,
                                        $reply,
                                        $this->user,
                                        $other,
                                        "reply",
                                        Func::dateFormat(),
                                        time()
                                    ];

                                    $inserting = new Insert(self::$db, "mentions", $subject, "");
                                    $action = $inserting->push($items, "siiiissi");
                                    
                                    if(!$action);
                                        return $action;
                                    endif;

                                endif;

                                /**
                                 * --------------- NOTIFICATION -----------
                                 */

                                // Making sure that a person cannot mention himself

                                if($this->user !== $other):
                                    // save the new reply mention notification here

                                    $data = [
                                        "element" => $reply,
                                        "elementType" => "reply",
                                        "user" => $this->user,
                                        "other" => $other,
                                        "type" => "mention",
                                        "parent" => $post,
                                        "more" => $comment
                                    ];
            
                                    $save = Notification::saveNotification(self::$db, $data, 'isiisisssi');

                                    if(!$save): return $save;
                                    // END //
                                endif;

                            else:
                                $this->content = "Mentioned person does not exist";
                            endif;

                        endforeach;

                        // There are no mentions or valid mentions
                        // Save the notification as comment rather than mention

                        if(!in_array(1, $arr)):
                            // Making sure that you do not get a notification when you edit your reply under your own reply

                            // Fetch reply owner id
                            $data = [
                                "id" => $reply,
                                "1" => "1",
                                "needle" => "user",
                                "table" => "replies"
                            ];

                            $replyOwner = Func::searchDb(self::$db, $data, "AND");

                            if($replyOwner !== $this->user):
                                // Save as notification
                                $data = [
                                    "element" => $reply,
                                    "elementType" => "reply",
                                    "user" => $this->user,
                                    "other" => $replyOwner,
                                    "type" => "comment",
                                    "parent" => $post,
                                    "more" => $comment
                                ];

                                $save = Notification::saveNotification(self::$db, $data, 'isiissssi');

                                if(!$save): return $save; endif;
                            endif;

                        else:
                            // Delete the previous comment that was saved as notification

                            $data = [
                                "type" => "reply",
                                "element" => $reply,
                                "elementType" => "reply",
                                "user" => $this->user 
                            ];

                            $action = Notification::deleteNotification(self::$db, $data);

                            if(!$action) return $action;

                        endif;

                    
                        self::$db->autocommit(true);

                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = [
                            "token" => $reply_token,
                            "content" => Func::mention(self::$db, $content, ['reply' => $reply])
                        ];

                    else:
                        $this->content = "Couldn't update reply";
                    endif;
                else:
                    $this->content = "Your reply is empty";
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

            $this->selecting->more_details("WHERE $key = ?# $val");
            $action = $this->selecting->action("other", "mentions");
            $this->selecting->reset();

            if($action != null) return $action;

            $value = $this->selecting->pull();
            if($value[1] > 0):
                foreach($value[0] as $val1):
                    $result = true;

                    // Get unedited mentioned person's username
                    $data = [
                        "id" => $val1['other'],
                        "1" => "1",
                        "needle" => "username",
                        "table" => "user" 
                    ];

                    $search = Func::searchDb(self::$db, $data, "AND");
                    if(is_string($search)) $mentioned = $search;

                    // If the mentioned from the database does not exist with the new mention
                    // Delete it
                    if(!in_array($mentioned, $mentions)):
                        $other = $val1['other'];
                        // Proceeding to delete the old mention
                        $deleting = new Delete(self::$db, "WHERE $key = ? AND other = ? AND user = ?, $val, $other, $this->user");
                        $result = $deleting->proceed("mentions");

                        // Deleting the mention notification also
                        $deleting = new Delete(self::$db, "WHERE element = ? AND other = ? AND user = ?, $val, $other, $this->user");
                        $result = $deleting->proceed("notification");

                    endif;

                endforeach;
            else:
                $result = true;
            endif;

            return $result;
        }

        public function save_mentions(array $mentions, array $data) {

            $key = array_keys($data);
            $value = array_values($data);

            // Insert mentions if there are any
            $date = Func::dateFormat();
            $time = time();

            foreach($mentions as $mention):

                // Check if the mention is valid
                $arr = [
                    "username" => $mention,
                    "1" => "1",
                    "needle" => "id",
                    "table" => "user"
                ];

                $search = Func::searchDb(self::$db, $arr, "AND");

                if(is_int($search)):

                    $other = $search;

                    $subject = [
                        "token",
                        "post",
                        "comment",
                        "reply",
                        "user",
                        "other",
                        "type",
                        "date",
                        "time"
                    ];
                    
                    $items = [
                        Func::tokenGenerator(),
                        $data['post'],
                        $data['comment'],
                        $data['reply'],
                        $this->user,
                        $other,
                        $data['type'],
                        $date,
                        $time
                    ];

                    // Saving the mentions
                    $inserting = new Insert(self::$db, "mentions", $subject, "");
                    $action = $inserting->push($items, "siiiiissi");
                    $inserting->reset();

                    if(!$action):
                        return $action;
                    else:
                        // Check wether the mention was in reply or comments

                        $element = 0;
                        if($data['type'] === "comment"):
                            $element = $data['comment'];
                        elseif($data['type'] === "reply"):
                            $element = $data['reply'];
                        endif;

                        // Save as notification
                        $arr = [
                            "element" => $element,
                            "elementType" => $data['type'],
                            "user" => $this->user,
                            "other" => $other,
                            "type" => "mention",
                            "parent" => $data['post'],
                            "more" => $data['more']
                        ];

                        $save = Notification::saveNotification(self::$db, $arr, 'isiisisssi');

                        if(!$save) return $save;
                    endif;
                endif;

            endforeach;
        }
    }
?>