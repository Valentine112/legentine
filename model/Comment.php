<?php
    namespace Model;

    use mysqli;
use Query\Select;
use Service\Response;

    class Comment extends Response {

        public static $db;

        public function __construct(mysqli $db, ?array $data)
        {
            self::$db = $db;
            $this->data = $data;

            $this->selecting = new Select(self::$db);

            return $this;
        }

        public function fetch_comment($post) : array {

            $this->selecting->more_details("WHERE post = ? LIMIT 20, $post");
            $action = $this->selecting->action("*", "comments");
            $this->selecting->reset();

            if($action != null) return $action;

            $this->type = "success";
            $this->status = 1;
            $this->message = "void";
            $this->content = $this->selecting->pull();

            return $this->deliver();
        }
    }
?>