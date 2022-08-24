<?php
    namespace Model;

    use mysqli;
    use Service\{
        Response,
        Func
    };

    use Query\{
        Delete,
        Insert,
        Select
    };

    class User extends Response {

        private static $db;

        public function __construct(mysqli $db, array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;
        }

        public function unlist() : array {
            $other = $this->data['val']['user'];

            if($other != $this->user):

                // Check if the person has already been unlisted by you
                // If he is, remove the person, else add the person
                $this->selecting->more_details("WHERE user = ? AND other = ?, $this->user, $other");
                $action = $this->selecting->action("id", "blocked_users");
                $this->selecting->reset();
                if($action != null):
                    return $action;
                endif;

                $value = $this->selecting->pull();
                if($value[1] > 0):
                    // User has already been unlisted
                    // Proceed to list the user back

                    $unlisted = $value[0][0]['id'];
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
                "post" => []
            ];

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


            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            return $this->deliver();

        }

        public function search() : array {
            $val = $this->data['val'];

            $arr = [];
            $result = [
                "person" => [],
                "post" => []
            ];

            // Fetch persons first
            $content = $val['content'];
            $contentRegExp = '%'.$content.'%';

            function searchExp(object $self, string $contentRegExp) : array {
                $self->selecting->more_details("WHERE fullname LIKE ? OR username LIKE ?, $contentRegExp, $contentRegExp");

                $action = $self->selecting->action("username", "user");
                $self->selecting->reset();
                if($action !== null) return $action;

                return $self->selecting->pull()[0];
            }


            // Fetch the first set of result that matches the whole content
            $value = searchExp($this, $contentRegExp);
            array_push($result, $value);

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


                // Fetch the first half content
                $value = searchExp($this, $firstContentExp);
                array_push($arr, $value);

                // Fetch the first half content
                $value = searchExp($this, $secondContentExp);
                array_push($arr, $value);

                print_r($arr);

                $result['person'] = array_merge(...$arr);

                print_r($result);
                
                $this->content = $result;

            endif;

            return $this->deliver();
        }

    }
?>