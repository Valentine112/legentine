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
                "type" => $this->type,
                "status" => $this->status,
                "message" => $this->message,
                "content" => $this->content,
                "time" => time()
            ];
        }

        public static function sendJSON(array|string $data) : string {
            /**
             * Method to send data as JSON format
             * --- Adding along the right headers
             */
            return json_encode($data);
        }
    }
?>