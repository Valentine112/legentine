<?php
    namespace Model;

    use mysqli;
    use Service\{
        Response,
        Func,
        Upload
    };

    use Query\{
        Delete,
        Insert,
        Select,
    Update
};

    class User extends Response {

        private static $db;

        public function __construct(mysqli $db, array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;
        }

        public function fetchUser() : array {
            $result = [];

            $box = [
                "person" => [],
                "self" => [
                    "user" => $this->user
                ],
                "more" => []
            ];

            $user = $this->data['val']['user'];

            $this->selecting->more_details("WHERE id = ?, $user");
            $action = $this->selecting->action("*", "user");
            $this->selecting->reset();

            if($action !== null) return $action;

            $value = $this->selecting->pull()[0];
            foreach($value as $val):
                $box['person'] = $val;

                $person = $val['id'];

                if($this->user !== $person):
                    // Check pinned
                    $this->selecting->more_details("WHERE user = ? AND other = ?, $this->user, $person");
                    $action = $this->selecting->action("token", "pin");
                    $this->selecting->reset();

                    if($action !== null) return $action;
                    $fetch = $this->selecting->pull();

                    $box['more']['pinned'] = $fetch[1] > 0 ? true : false;

                    // Check Listed
                    $this->selecting->more_details("WHERE user = ? AND other = ?, $this->user, $person");
                    $action = $this->selecting->action("token", "blocked_users");
                    $this->selecting->reset();

                    if($action !== null) return $action;
                    $fetch = $this->selecting->pull();

                    $box['more']['listed'] = $fetch[1] > 0 ? true : false;

                    // Fetching user summedRating and totalRating
                    $this->selecting->more_details("WHERE other = ?, $person");
                    $action = $this->selecting->action("SUM(rate), COUNT(id)", "ratings");
                    $this->selecting->reset();

                    if($action !== null) return $action;
                    $fetch = $this->selecting->pull();

                    $summedRating = $fetch[0][0]["SUM(rate)"] === null ? 0 : $fetch[0][0]["SUM(rate)"];

                    $totalRating = $fetch[0][0]["COUNT(id)"] === null ? 0 : $fetch[0][0]["COUNT(id)"];

                    $box['more']['summedRating'] = $summedRating;
                    $box['more']['totalRating'] = $totalRating;

                    // Check if user has rated
                    $data = [
                        "user" => $this->user,
                        "other" => $person,
                        "needle" => "rate",
                        "table" => "ratings"
                    ];
    
                    $search = Func::searchDb(self::$db, $data);

                    $box['more']['rated'] = is_int($search) ? $search : "";

                endif;

                array_push($result, $box);
            endforeach;

            
            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            return $this->deliver();
        }

        public function unlist() : array {
            $other = $this->data['val']['user'];

            if($other != $this->user):

                // Check if the person has already been unlisted by you
                // If he is, remove the person, else add the person
                $data = [
                    "user" => $this->user,
                    "other" => $other,
                    "needle" => "id",
                    "table" => "blocked_users"
                ];

                $search = Func::searchDb(self::$db, $data);

                if(is_int($search)):
                    // User has already been unlisted
                    // Proceed to list the user back

                    $unlisted = $search;
                    $deleting = new Delete(self::$db, "WHERE id = ?, $unlisted");
                    $action = $deleting->proceed("blocked_users");

                    if($action):
                        // If everything i set, commit to true and send a positive response
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = "Listed";

                    else:
                        return $action;

                    endif;
                else:
                    // User has not been listed yet
                    // Proceed to list unlist user

                    $subject = [
                        "token",
                        "user",
                        "other",
                        "date",
                        "time"
                    ];

                    $items = [
                        Func::tokenGenerator(),
                        $this->user,
                        $other,
                        Func::dateFormat(),
                        time()
                    ];

                    $inserting = new Insert(self::$db, "blocked_users", $subject, "");
                    $action = $inserting->push($items, 'siisi');
                    $inserting->reset();

                    if($action):
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = "Unlisted";
                    
                    else:
                        return $action;

                    endif;

                endif;

            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "void";
                $this->content = "You cannot unlist yourself";

            endif;

            return $this->deliver();
        }

        public function openSearch() : array {
            $self = $this;
            
            $result = [
                "people" => [],
                "post" => [],
                "recent" => [],
                "user" => []
            ];

            // Using this opportunity to also fetch the user logged in
            // This would be for the sidebar menu

            $this->selecting->more_details("WHERE id = ?, $this->user");
            $action = $this->selecting->action("id, fullname, username, photo", "user");
            $this->selecting->reset();

            if($action != null) return $action;

            $result['user'] = $this->selecting->pull()[0];

            // Select some top rated random persons apart from myself
            // Get people that are rated 4 and above
            (int) $ratingMargin = 4;
            (int) $people = 4;

            $this->selecting->more_details("WHERE id <> ? AND rating >= ? ORDER BY RAND() LIMIT $people, $self->user, $ratingMargin");

            $action = $this->selecting->action("id, fullname, username, photo", "user");
            $self->selecting->reset();

            if($action != null) return $action;

            $result['people'] = $this->selecting->pull()[0];

            // Select some top starred post apart from my post
            (int) $starMargin = 1;
            $this->selecting->more_details("WHERE user <> ? AND stars >= ? ORDER BY RAND() LIMIT 4, $this->user, $starMargin");
            $action = $this->selecting->action("token, title, content", "post");
            $this->selecting->reset();

            if($action != null) return $action;

            $result['post'] = $this->selecting->pull()[0];

            // Fetch the recent searches from this user
            $this->selecting->more_details("WHERE user = ? ORDER BY id DESC LIMIT 10, $this->user");
            $action = $this->selecting->action("token, username", "recent");
            $this->selecting->reset();

            if($action != null) return $action;

            $result['recent'] = $this->selecting->pull()[0];

            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            return $this->deliver();

        }

        public function search() : array {
            $val = $this->data['val'];

            $arr = [];
            $result = [];

            $content = $val['content'];
            $limit = $val['limit'];
            $contentRegExp = '%'.$content.'%';

            function searchPeopleExp(object $self, string $contentRegExp, int $limit) : array {
                $result = [];

                $self->selecting->more_details("WHERE fullname LIKE ? OR username LIKE ? AND id <> ? LIMIT $limit, $contentRegExp, $contentRegExp, $self->user");

                $action = $self->selecting->action("id, fullname, username, photo", "user");
                $self->selecting->reset();
                if($action !== null) return $action;

                $value = $self->selecting->pull();
                foreach($value[0] as $val):
                    $val['type'] = "people";
                    $val['sort'] = $val['username'];
                    $val['filter'] = $val['id'];

                    array_push($result, $val);
                endforeach;

                return $result;
            }

            function searchPostExp(object $self, string $contentRegExp, int $limit) : array {
                $result = [];
                $self->selecting->more_details("WHERE title LIKE ? LIMIT $limit, $contentRegExp");

                $action = $self->selecting->action("token, title, content", "post");
                $self->selecting->reset();
                if($action !== null) return $action;

                $value = $self->selecting->pull();
                foreach($value[0] as $val):
                    $val['type'] = "post";
                    $val['sort'] = $val['title'];
                    $val['filter'] = $val['token'];

                    array_push($result, $val);
                endforeach;

                return $result;
            }

            // Fetch the first set of result that matches the whole content from people

            $value = searchPeopleExp($this, $contentRegExp, $limit);
            array_push($arr, $value);

            $value = searchPostExp($this, $contentRegExp, $limit);
            array_push($arr, $value);
            // Split the search value in 2 to get results from the first side
            // And get the result from the second side
            // This should be done if the length of the search is greater than 4

            $contentLength = strlen(trim($content));
            if($contentLength > 3):
                $halfContent = $contentLength / 2;

                if(!is_float($halfContent)):
                    $firstHalf = substr($content, 0, $halfContent);
                    $secondHalf = substr($content, $halfContent);

                else:
                    $firstHalf = substr($content, 0, ceil($halfContent));
                    $secondHalf = substr($content, floor($halfContent));
                endif;

                $firstContentExp = '%'.$firstHalf.'%';
                $secondContentExp = '%'.$secondHalf.'%';

                // Fetch the first half content from people
                $value = searchPeopleExp($this, $firstContentExp, $limit);
                array_push($arr, $value);

                // Fetch the second half content from people
                $value = searchPeopleExp($this, $secondContentExp, $limit);
                array_push($arr, $value);

            endif;

            $result = array_merge(...$arr);

            // remove recurssive
            $a = [];
            foreach($result as $res):
                // Check if the type and filter matches
                // If it does, then its the same object
                // Pick only one
                $search = Func::searchObject($a, $res['filter'], 'filter');
                if(!in_array(1, $search)):
                    array_push($a, $res);
                endif;

            endforeach;

            $filtering = array_column($a, 'sort');
            array_multisort($filtering, SORT_ASC, $a);

            $result = $a;


            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            return $this->deliver();
        }

        public function rateUser() : array {
            // Declaring the general error message first
            $this->type = "error";
            $this->status = 0;
            $this->message = "void";


            $val = $this->data['val'];
            (int) $rating = $val['rating'];
            $other = $val['other'];

            

            // Make sure that you are not rating yourself
            if($this->user !== $other):
                // Making sure that the rating value is within this range
                if($rating > 0 && $rating < 6):
                    self::$db->autocommit(false);

                    $action = false;

                    // Check if user has rated before
                    // If so, update instead of insert
                    $data = [
                        "user" => $this->user,
                        "other" => $other,
                        "needle" => "id",
                        "table" => "ratings"
                    ];
    
                    $search = Func::searchDb(self::$db, $data);

                    if(!is_int($search)):
                        // Create a new rating

                        $subject = [
                            "token",
                            "user",
                            "other",
                            "rate",
                            "date",
                            "time"
                        ];

                        $items = [
                            Func::tokenGenerator(),
                            $this->user,
                            $other,
                            $rating,
                            Func::dateFormat(),
                            time()
                        ];

                        $inserting = new Insert(self::$db, "ratings", $subject, "");
                        $action = $inserting->push($items, 'siiisi');
                        $inserting->reset();
                    else:

                        // Update the rating
                        $ratingsId = $search;

                        $updating = new Update(self::$db, "SET rate = ? WHERE id = ?# $rating# $ratingsId");
                        $action = $updating->mutate('ii', 'ratings');

                    endif;

                    if($action):
                        // Calculate the current ratings and update in user table
                        // First select and sum all the ratings from this user
                        $this->selecting->more_details("WHERE other = ?, $other");
                        $action = $this->selecting->action("SUM(rate), COUNT(id)", "ratings");
                        $this->selecting->reset();

                        if($action !== null) return $action;

                        $summedRating = $this->selecting->pull()[0][0]["SUM(rate)"];
                        $totalRating = $this->selecting->pull()[0][0]["COUNT(id)"];

                        $calcRating = $summedRating / $totalRating;

                        // Update the rating value in the user table
                        $updating = new Update(self::$db, "SET rating = ? WHERE id = ?# $calcRating# $other");
                        $action = $updating = $updating->mutate("si", "user");

                        if($action):
                            self::$db->autocommit(true);

                            $result = [
                                "summedRating" => $summedRating,
                                "totalRating" => $totalRating,
                                "rated" => $rating,
                                "calcRating" => $calcRating
                            ];

                            $this->type = "success";
                            $this->status = 1;
                            $this->message = "void";
                            $this->content = $result;

                        else:
                            return $action;
                        endif;
                    
                    else:
                        return $action;

                    endif;
                else:
                    $this->content = "Rating range should be between 1 - 5";
                endif;
            else:
                $this->content = "You cannot rate yourself";

            endif;

            return $this->deliver();
        }

        public function pin() : array {
            // Declaring the general error message first
            $this->type = "error";
            $this->status = 0;
            $this->message = "void";

            $other = $this->data['val']['other'];

            if(!empty($other)):
                // Check if other has been pinned by user
                $data = [
                    "user" => $this->user,
                    "other" => $other,
                    "needle" => "id",
                    "table" => "pin"
                ];

                $search = Func::searchDb(self::$db, $data);

                $this->type = "success";
                $this->status = 1;
                $this->message = "void";

                if(is_int($search)):
                    // Unpin user
                    $pinId = $search;
                    $deleting = new Delete(self::$db, "WHERE id = ?, $pinId");
                    $action = $deleting->proceed("pin");
                    if($action):
                        $this->content = "unpin";
                    else:
                        return $action;
                    endif;

                else:
                    // Pin user
                    $subject = [
                        "token",
                        "user",
                        "other",
                        "date",
                        "time"
                    ];

                    $items = [
                        Func::tokenGenerator(),
                        $this->user,
                        $other,
                        Func::dateFormat(),
                        time()
                    ];

                    $inserting = new Insert(self::$db, "pin", $subject, "");
                    $action =$inserting->push($items, "siisi");
                    if($action):
                        $this->content = "pin";
                    else:
                        return $action;
                    endif;
                endif;
            else:
                $this->content = "Other id should not be empty";
            endif;

            return $this->deliver();
        }

        public function uploadPhoto() : array {
            // Declaring the general error message first
            $this->type = "error";
            $this->status = 0;
            $this->message = "void";

            $val = $this->data['val'];

            $uploading = new Upload("src/photo", "photo", $val['files']);
            $savingImage = $uploading->saveImage();

            if($savingImage['status'] === 1):
                $content = $savingImage['content'];

                // Check if there was an error in any of the uploads and abort the whole process if so
                // If the is 0 in the content, it means there was an error
                if(!in_array(0, $content)):
                    // Check the upload type
                    if($val['type'] === "profilePicture"):
                        $path = $content[0];
                        
                        $updating = new Update(self::$db, "SET photo = ? WHERE id = ?# $path# $this->user");
                        $action = $updating->mutate('si', 'user');

                        if($action):
                            $this->type = "success";
                            $this->status = 1;
                            $this->message = "void";
                            $this->content = "profilePicture%%$path";
                        else:
                            return $action;
                        endif;

                    elseif($val['type'] === "uploadPicture"):
                        self::$db->autocommit(false);

                        $subject = [
                            "token",
                            "user",
                            "photo",
                            "date",
                            "time"
                        ];

                        $action = "";

                        // check the mode
                        if($val['mode']):
                            foreach($content as $val):
                                $items = [
                                    Func::tokenGenerator(),
                                    $this->user,
                                    $val,
                                    Func::dateFormat(),
                                    time()
                                ];

                                $inserting = new Insert(self::$db, "gallery", $subject, "");
                                $action = $inserting->push($items, 'sissi');

                            endforeach;
                        else:
                            $items = [
                                Func::tokenGenerator(),
                                $this->user,
                                implode("%%", $val),
                                Func::dateFormat(),
                                time()
                            ];

                            $inserting = new Insert(self::$db, "gallery", $subject, "");
                            $action = $inserting->push($items, 'sissi');

                        endif;

                        if($action):
                            $this->type = "success";
                            $this->status = 1;
                            $this->message = "void";
                            $this->content = "uploadPicture";
                        else:
                            return $action;
                        endif;

                        self::$db->autocommit(true);
                    endif;

                else:
                    $this->content = "Error in uploading file";
                endif;
            else:
                return $savingImage;
            endif;

            return $this->deliver();
        }

    }
?>