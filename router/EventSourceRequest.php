<?php
    /*header('Cache-Control: no-store');
    header('Content-Type: text/event-stream');*/

    /**
     * Fetching data from the client
     * ---- This mode creates accessibility for EventSource
    */

    namespace Router;

    use Service\{
        Func,
        Response
    };

    use mysqli;

    class EventSourceRequest{

        private array $data;
        private array $actions;
        private static $db;

        function __construct(mysqli $db){
            $this->result = "";
            self::$db = $db;

        }

        private function save_route(string $method, string $action, callable|array $callback) : bool {
            $this->actions[$method][$action] = $callback;

            return true;
        }

        public function is_get(string $action, callable|array $callback) {
            if(strtoupper($_SERVER['REQUEST_METHOD']) === "GET"):
                foreach($_GET as $ind => $val):
                    $_GET[$ind] = Func::cleanData($val, 'string');
                endforeach;

                $this->data = $_GET;
            endif;

            $this->save_route('get', $action, $callback);

        }
        
        public function listen() {
            $req = strtolower(Func::cleanData($_SERVER['REQUEST_METHOD'], 'string'));

            $data = $this->data;

            $requestedAction = $this->actions[$req][$data['part']] ?? null;

            if(is_callable($requestedAction) && is_string($requestedAction)):
                $this->result = call_user_func($requestedAction);

            endif;

            if(is_iterable($requestedAction)):
                [$class, $method] = $requestedAction;
                $this->result = (new $class(self::$db))->$method($this->data);
                
            endif;

        }

        public function send(int $delay) {
            $more = $this->result['more'];

            echo "event: LT-$more\n";
            echo "data: ".json_encode($this->result)."";
            echo PHP_EOL.PHP_EOL;

            if(connection_aborted()) exit();

            ob_end_flush();
            flush();

            sleep($delay);
        }

    }

?>