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

    // Other in feature is the person that the request is been sent to
    // User is the person who made the request to be feature

    class Feature extends Response {

        private static $db;

        public function __construct(mysqli $db, ?array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;

            $this->pending = 0;
            $this->accepted = 1;

            return $this;
        }

        public function fetchRequest(?int $post) : array {
            // Photo does not belong to user
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $order = "ORDER BY id DESC LIMIT 1";

            $result = [
                'content' => [],
                'self' => $this->user
            ];

            // Check wether all the features are fetched or the features relating to a particular post
            if(is_int($post)):
                // The feature relating to a particular post is fetched
                $this->selecting->more_details("WHERE post = ?# $post");
            else:
                // All the features are fetched
                $type = $this->data['val']['type'];

                // Configuring the data to be able to fetch depending on the pages its been pulled from

                $query = "";
                $queryParam = "";

                // If isset 'new', this means that the data is been sent from moreData
                // If so, we modify the data

                if(isset($this->data['val']['new'])):
                    $query = $this->data['val']['query'];
                    $queryParam = "# ".$this->data['val']['value'];

                endif;

                if($type === "request"):
                    $this->selecting->more_details("WHERE other = ? AND response = ? $query $order# $this->user# $this->pending"."$queryParam");

                elseif($type === "history"):
                    $this->selecting->more_details("WHERE user = ? OR other = ? $order# $this->user# $this->user");

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
                    "id" => $val['user'],
                    "1" => "1",
                    "needle" => "*",
                    "table" => "user"
                ];
    
                $search = Func::searchDb(self::$db, $data, "AND");
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
    
                $search = Func::searchDb(self::$db, $data, "AND");
                if(!empty($search) || $search != null):
                    $tempResult['post'] = $search;
                endif;

                $tempResult['feature'] = $val;
                $tempResult['type'] = "feature";

                // creating this speciifcally to sort the notification and feature when mixed together
                // The main aim is that the keys are the same when accessing the time or date

                $tempResult['sortMethod'] = [
                    "sortTime" => $val['time'],
                    "sortDate" => $val['date']
                ];

                array_push($result['content'], $tempResult);
            endforeach;

            $this->content = $result;

            return $this->deliver();
        }

        public function fetchHistory() : array {
            // Photo does not belong to user
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            $order = "ORDER BY id DESC LIMIT 1";

            $result = [
                'content' => [],
                'self' => $this->user
            ];

            // Configuring the data to be able to fetch depending on the pages its been pulled from

            $query = "";
            $queryParam = "";

            // If isset 'new', this means that the data is been sent from moreData
            // If so, we modify the data

            if(isset($this->data['val']['new'])):
                $query = $this->data['val']['query'];
                $queryParam = "# ".$this->data['val']['value'];

            endif;

            $this->selecting->more_details("WHERE user = ? OR other = ? $query $order# $this->user# $this->user"."$queryParam");
            
            $action = $this->selecting->action('*', 'history');
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
    
                $search = Func::searchDb(self::$db, $data, "AND");
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
    
                $search = Func::searchDb(self::$db, $data, "AND");
                if(!empty($search) || $search != null):
                    $tempResult['post'] = $search;
                endif;

                $tempResult['history'] = $val;

                array_push($result['content'], $tempResult);
            endforeach;

            $this->content = $result;

            return $this->deliver();
        }

        public function request() : array {
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

            $search = Func::searchDb(self::$db, $data, "AND");
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

                    $search = Func::searchDb(self::$db, $data, "AND");
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
            
                        $search = Func::searchDb(self::$db, $data, "AND");
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
            // Photo does not belong to user
            $this->type = "error";
            $this->status = 1;
            $this->message = "fill";

            $val = $this->data['val'];

            $type = $val['type'];
            $token = $val['token'];
            
            self::$db->autocommit(FALSE);

            // Fetch post id
            $data = [
                "token" => $token,
                "1" => "1",
                "needle" => "post",
                "table" => "feature"
            ];

            $post = Func::searchDb(self::$db, $data, "AND");

            // Fetch other id
            $data['needle'] = "user";

            $other = Func::searchDb(self::$db, $data, "AND");

            // Fetch feature id
            $data['needle'] = "id";

            $feature = Func::searchDb(self::$db, $data, "AND");

            if($type == 0):
                // Request declined
                // Delete request

                // Process request from History also
                // Convert the feature Id to token so it can also work here
                // Fetch post id
                if(isset($val['feature'])):
                    $data = [
                        "id" => $val['feature'],
                        "1" => "1",
                        "needle" => "token",
                        "table" => "feature"
                    ];

                    $token = Func::searchDb(self::$db, $data, "AND");
                endif;

                $deleting = new Delete(self::$db, "WHERE token = ? AND other = ?, $token, $this->user");
                $action = $deleting->proceed("feature");
                if($action):
                    $this->content = "Request delined";
                else:
                    return $action;
                endif;

            elseif($type == 1):
                // Request accepted
                $updating = new Update(self::$db, "SET response = ? WHERE other = ? AND token = ?# $this->accepted# $this->user# $token");
                $action = $updating->mutate('iis', 'feature');
                if($action):
                    $this->type = "success";
                    $this->content = "Request accepted";
                else:
                    return $action;
                endif;

            endif;

            // Confirming that every previous transaction was successful and proceeding
            //Save to history

            if($this->status === 1):

                // If request is coming from fetchHistory
                if(isset($val['feature'])):
                    $historyFeature = $val['feature'];

                    // Check if there is already a history representing that feature and update if there is

                    // Fetch post id
                    $data = [
                        "feature" => $historyFeature,
                        "1" => "1",
                        "needle" => "id",
                        "table" => "history"
                    ];

                    $history = Func::searchDb(self::$db, $data, "AND");
                    if(is_int($history)):

                        $updating = new Update(self::$db, "SET response = ? WHERE id = ? AND user = ?# $type# $history# $this->user");
                        $action = $updating->mutate('iii', 'history');
                        if($action):
                            self::$db->autocommit(TRUE);
                        else:
                            return $action;
                        endif;
                    endif;

                else:
                    $subject = [
                        "token",
                        "feature",
                        "post",
                        "user",
                        "other",
                        "response",
                        "date",
                        "time"
                    ];

                    $items = [
                        Func::tokenGenerator(),
                        $feature,
                        $post,
                        $this->user,
                        $other,
                        $type,
                        Func::dateFormat(),
                        time()
                    ];

                    $inserting = new Insert(self::$db, "history", $subject, "");
                    $action = $inserting->push($items, 'siiiiisi');
                    if($action):
                        self::$db->autocommit(TRUE);
                    else:
                        return $action;
                    endif;
                endif;

            endif;

            return $this->deliver();
        }

        public function quiet() : array {

            $this->type = "success";
            $this->status = 1;
            $this->message = "void";

            // Fetch feature quiet
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "quiet",
                "table" => "user"
            ];

            $quiet = Func::searchDb(self::$db, $data, "AND");

            $quiet === 0 ? $quiet = 1 : $quiet = 0;

            $updating = new Update(self::$db, "SET quiet = ? WHERE id = ?# $quiet# $this->user");
            $action = $updating->mutate('ii', 'user');
            
            if($action):
                $this->content = $quiet;
            else:
                return $action;
            endif;

            return $this->deliver();
        }

    }
?>