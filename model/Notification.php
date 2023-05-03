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
                    "other" => $user
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
            /**
             * The items that are identified are those with a status of 0
             * The signals are sent to NOTIFCATION & FEATURE
            */

            $result = [
                "notification" => [
                    "status" => ""
                ],

                "feature" => [
                    "status" => ""
                ]
            ];

            // --------- Notification ----------- //
            $data = [
                "other" => $this->user,
                "status" => 0,
                "needle" => "id",
                "table" => "notification" 
            ];

            $notification = Func::searchDb(self::$db, $data, "AND");

            is_int($notification) ? $result['notfication']['status'] = 1 : $result['notfication']['status'] = 0;

            // --------- Feature ----------- //
            $data['table'] = "feature";

            $feature = Func::searchDb(self::$db, $data, "AND");

            is_int($feature) ? $result['feature']['status'] = 1 : $result['feature']['status'] = 0;

            return $this->deliver();
        }

    }