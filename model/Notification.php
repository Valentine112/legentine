<?php
    namespace Model;

    /**
     * The comment, reply, mention, and top post would be treated as notifications
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

        public function fetchNotification() : array {

            $this->status = 1;
            $this->type = "success";
            $this->message = "void";

            $result = [
                "mentions" => "",
                "comments" => "",
                "tops" => ""
            ];

            // Fetch mentions first
            $this->selecting->more_details("WHERE user = ?, $this->user");
            $action = $this->selecting->action("*", "mentions");
            if($action != null) return $action;

            $value = $this->selecting->pull();

            $result['mentions'] = $value[0];

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

            $notificationType = $data["type"];
            $element = $data["element"];
            $user = $data["user"];

            $deleting = new Delete($db, "WHERE type = ? AND element = ? AND user = ?, $notificationType, $element, $user");
            $action = $deleting->proceed("notification");

            if($action):
                return TRUE;
            else:
                return $action;
            endif;
        }

    }