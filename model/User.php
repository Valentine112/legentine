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

            function getRandomPeople(object $self, int $people, int $ratingMargin) : array {
                $self->selecting->more_details("WHERE id <> ? AND rating > ? ORDER BY RAND() LIMIT $people, $self->user, $ratingMargin");
                $action = $self->selecting->action("id, username, photo", "user");
    
                if($action != null) return $action;
    
                $value = $self->selecting->pull();

                return $value;
            }

            $value = getRandomPeople($this, $people, $ratingMargin);
            
            // If the number of people with 4 ratings and above are not enough, get the remaining that are 3 and above

            if($value[1] < 4):
                $people -= $value[1];
                (int) $ratingMargin = 3;

                $value1 = getRandomPeople($this, $people, $ratingMargin);

                array_push($value[0], ...$value1[0]);
            endif;

            $result['people'] = $value[0];



            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $result;

            
        }

        public function search() : array {
            $val = $this->data['val'];

            // Fetch persons first
            return $this->deliver();
        }

    }
?>