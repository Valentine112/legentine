<?php
    namespace Model;

    /**
     * The comment, reply, mention, and top post would be treated as notifications
     * The person receiving the notification would be saved as other
     * While the person creating the notification would be saved as user
     * 
     * Comment->save_mentions is already saving the mentions notification for either comments or reply
     */

    use mysqli;
    use Service\{
        Response,
        Func,
    };

    use Query\{
        Delete,
        Insert,
        Select,
        Update
    };

    class Notification extends Response {

        private static $db;

        public function __construct(mysqli $db, array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;
        }

        public function fetchNotification(?array $filter) : array {

            $this->status = 1;
            $this->type = "success";
            $this->message = "void";

            $result = [];

            $filterQuery = "";
            $filterValue = "";

            // The checks if the notification are been fetched from controller
            // Or fetch directly from another source
            if($filter !== null):
                $filterQuery = $filter["query"];
                $filterValue = "# ".$filter["filter"];

            endif;

            // Fetch notification
            $this->selecting->more_details("WHERE other = ? $filterQuery ORDER BY id DESC LIMIT 20# $this->user"."$filterValue");
            $action = $this->selecting->action("*", "notification");
            if($action != null) return $action;

            $value = $this->selecting->pull();

            foreach($value[0] as $noti):

                // Fetch the parent post
                $data = [
                    "id" => $noti["parent"],
                    "1" => "1",
                    "needle" => "*",
                    "table" => "post"
                ];

                $post = Func::searchDb(self::$db, $data, "AND");

                // END

                // Fetch the other
                $data["id"] = $noti['user'];
                $data['table'] = "user";

                $user = Func::searchDb(self::$db, $data, "AND");

                // END

                // Fetch the content, wether reply or comment
                $data['id'] = $noti['element'];

                // Set the appropriate table
                if($noti['elementType'] === "comment"):
                    $data["table"] = "comments";

                elseif($noti['elementType'] === "reply"):
                    $data["table"] = "replies";
                
                endif;

                $content = Func::searchDb(self::$db, $data, "AND");

                // Fetch the comment token using the comment id
                if($noti['elementType'] === "reply"):
                    $data = [
                        "id" => $content['comment'],
                        "1" => "1",
                        "needle" => "token",
                        "table" => "comments"
                    ];

                    $content['comment'] = Func::searchDb(self::$db, $data, "AND");

                endif;

                // END


                $arr = [
                    "notification" => $noti,
                    "content" => $content,
                    "self" => $this->user,
                    "post" => $post,
                    "other" => $user,
                    "type" => "notification",
                    "sortMethod" => [
                        "sortTime" => $noti['time'],
                        "sortDate" => $noti['date']
                    ]
                ];

                array_push($result, $arr);

            endforeach;

            $this->content = $result;

            return $this->deliver();
        }

        public static function saveNotification(mysqli $db, array $data, string $type) : bool|array {

            $data["token"] = Func::tokenGenerator();
            $data["date"] = FUnc::dateFormat();
            $data["time"] = time();

            $keys = array_keys($data);
            $value = array_values($data);

            // First delete any previous notification that matches this one

            $deleting = self::deleteNotification($db, $data);
            if($deleting):
                $inserting = new Insert($db, "notification", $keys, "");
                $action = $inserting->push($value, $type);
                if($action):
                    return TRUE;
                else:
                    return $action;
                endif;
            else:
                return $deleting;
            endif;
        }

        public static function deleteNotification(mysqli $db, array $data) : bool|array {

            // Deleting with the type, element, user, elementType and other
            // All this attributes are very important and must be met
            // It ensures that the particular element is deleted

            $notificationType = $data["type"];
            $element = $data["element"];
            $user = $data["user"];
            $elementType = $data['elementType'];

            if(!isset($data['other'])):
                $otherStr = "1";
                $other = "1";
            else:
                $otherStr = "other";
                $other = $data['other'];
            endif;

            $deleting = new Delete($db, "WHERE type = ? AND element = ? AND user = ? AND elementType = ? AND $otherStr = ?, $notificationType, $element, $user, $elementType, $other");
            $action = $deleting->proceed("notification");

            if($action):
                return TRUE;
            else:
                return $action;
            endif;
        }

        public function liveNotification() {
            $this->status = 1;
            $this->type = "success";
            $this->message = "void";
            /**
             * The items that are identified are those with a status of 0
             * The duration is 24 hours
             * The signals are sent to NOTIFCATION & FEATURE
            */

            $duration = time() - time();
            $zero = 0;

            $result = [];

            // --------- Notification ----------- //
            $data = [
                "filter" => $zero."# ".$duration,
                "query" => "AND status = ? AND time >= ?",
                "from" => "notification",
                "new" => 1
            ];

            $result = $this->fetchNotification($data)['content'];

            $data = [
                "val" => [
                    "value" => $zero."# ".$duration,
                    "query" => "AND status = ? AND time >= ?",
                    "from" => "notification",
                    "type" => "request",
                    "new" => 1
                ]
            ];

            $feature = new Feature(self::$db, $data, $this->user);

            // Joining the feature and notifications together for sorting
            array_push($result, ...$feature->fetchRequest(null)['content']['content']);

            // Preventing errors when accessing keys that does not exist
            if(count($result) > 0):
                // Sorted them in a descending order using their time
                // This ensures that the newest is at the top
                $key_values = Func::array_column_recursive($result, "sortTime");
                array_multisort($key_values, SORT_DESC, $result);

                // Trim them, making sure that they don't extend 20
                $resultCount = count($result);
                if($resultCount > 20)
                    $trim = -($resultCount - 20);
                    $result = array_slice($result, 0, $trim);

            endif;

            $this->more = $this->user;
            $this->content = $result;

            return $this->deliver();
        }

        public function changeStatus(?int $filter) : array {

            $this->status = 1;
            $this->type = "success";
            $this->message = "void";

            $val = $this->data['val'];

            $result = [];
            (int) $zero = 0;

            // When a particular one needs to be updated and not just all
            if($val['filter']):
                // First sort the data by their table
                // So we update one table after the other
                usort($val['content'], function($a, $b) {
                    return strcmp($a['type'], $b['type']);
                });

                foreach($val['content'] as $item):
                    $token = $item['token'];
                    $table = $item['type'];

                    $updating = new Update(self::$db, "SET status = ? WHERE token = ? AND other = ?# $filter# $token# $this->user");
                    $action = $updating->mutate('isi', $table);

                    if(!$action) return $action;
                endforeach;

            else:
                $table = $val['table'];

                $updating = new Update(self::$db, "SET status = ? WHERE other = ? AND status = ?# $filter# $this->user# $zero");
                $action = $updating->mutate('iii', $table);

                if(!$action) return $action;
            endif;
            

            // Update the status to seen // Seen is 1, View is 2

            return $this->deliver();

        }

        public function tops() : array {
            /** 
             * Check the tops based on SECTION AND TIME
             * SECTION - Rap, Song, Poem, Comedy, Story, All
             * TIME - All time & Weekly
             * ------------------ Logic -----------------
             * Store the sections in an array, then loop through each of them
             * Fetch the top 15 most liked post on that section for that week, and next for all time
             * The ordering should be based on likes and time
             * Then proceed to save the results in an array with the necessary keys and values to identify them particularly
            */

            $result = [];
            $sections = ["Rap", "Song", "Poem", "Comedy", "Story", "All"];
            (int) $allTime = 0;
            (int) $zero = 0;
            $one = "1";
            $week = time() - (((60 * 60) * 24) * 7);

            foreach($sections as $section):
                // Check the section
                // First pre define the values
                $category = "category = ? AND";
                $types = 'sii';
                $values = [$section, $allTime, $zero];

                // Modify the value is the section is ALL
                if($section == "All"):
                    $category = "1 = ? AND";
                    $values = [$one, $allTime, $zero];
                endif;

                $query = "SELECT * FROM post WHERE $category time >= ? AND stars > ? ORDER BY stars DESC LIMIT 15";
                
                $selecting = self::$db->prepare($query);

                // Checking for the all time
                $selecting->bind_param($types, ...$values);
                $selecting->execute();
                $a = $selecting->get_result();

                // Checking for weekly now
                $values[1] = $week;
                $selecting->bind_param($types, ...$values);
                $selecting->execute();
                $b = $selecting->get_result();


                $all = $a->fetch_all(MYSQLI_ASSOC);
                $weekly = $b->fetch_all(MYSQLI_ASSOC);

                // Loop through each of them and verify if the user post is among
                // Then add some extra keys to the object for more identification

                // Looping through all first
                foreach($all as $a):
                    if(!empty($a)):
                        // Check if the ID belongs to the user

                        if($a['user'] === $this->user):
                            $arr = [
                                "post" => $a,
                                "section" => $section,
                                "type" => "General",
                                "sortMethod" => [
                                    "sortTime" => $a['time'],
                                    "sortDate" => $a['date']
                                ]
                            ];

                            array_push($result, $arr);

                        endif;
                    endif;

                endforeach;

                // Looping through weekly next
                foreach($weekly as $b):
                    if(!empty($a)):
                        // Check if the ID belongs to the user

                        if($a['user'] === $this->user):
                            $arr = [
                                "post" => $a,
                                "section" => $section,
                                "type" => "Weekly",
                                "sortMethod" => [
                                    "sortTime" => $a['time'],
                                    "sortDate" => $a['date']
                                ]
                            ];

                            array_push($result, $arr);

                        endif;
                    endif;
                endforeach;

                // Sort the contents
                $key_values = Func::array_column_recursive($result, "stars");
                array_multisort($key_values, SORT_DESC, $result);

                $this->content = $result;

            endforeach;

            // Setting the more to the user ID when trying to fetch from eventSource
            $this->more = $this->user;
            
            return $this->deliver();
        }

    }