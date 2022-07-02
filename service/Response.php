<?php
    namespace Service;

    class Response {

        public $status;
        public $type;
        public $message;
        public $content;

        public function deliver() : array {
            return [
                "status" => $this->status,
                "message" => $this->message,
                "content" => $this->content,
                "time" => time()
            ];
        }

        public static function sendJSON(array|string $data) : array|string {

            return json_encode($data);
        }
    }
?>