<?php
    namespace Service;

    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');

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

        public function sendJSON() : array|string {

            return json_encode($this->deliver());
        }
    }
?>