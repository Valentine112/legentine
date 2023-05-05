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

        public static function sendJSON(array|string $data) : string|false {
            /**
             * Method to send data as JSON format
             * --- Adding along the right headers
             */
            return json_encode($data);
        }

        public static function sendEventSource(int $delay,) {

            echo "event: user\n";
            echo "data: ".json_encode($this->result)."";
            echo PHP_EOL.PHP_EOL;

            if(connection_aborted()) exit();

            ob_end_flush();
            flush();

            sleep($delay);
        }
    }
?>