<?php
    namespace Service;

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

        public static function sendJSON(array|string $data) : string|false {
            /**
             * Method to send data as JSON format
             * --- Adding along the right headers
             */
            return json_encode($data);
        }
    }
?>