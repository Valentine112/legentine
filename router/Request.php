<?php

    /**
     * Fetching data from the client
     * ---- Methods currently allowed are GET and POST
    */

    namespace Router;

    use Service\Response;
    use Service\Func;
    use mysqli;
    
    class Request extends Response {

        private array $data;
        private array $actions;
        private static $db;

        function __construct(mysqli $db) {
            self::$db = $db;
        }

        private function save_route(string $method, string $action, callable|array $callback) : bool {
            $this->actions[$method][$action] = $callback;

            return true;
        }

        public function is_post(string $action, callable|array $callback) : array|false {

            // Check if the request method is post
            if(isset($_POST) || strtoupper($_SERVER["REQUEST_METHOD"]) === "POST"):
                // Make sure Content-type is application/json
                $contentType = ($_SERVER['CONTENT_TYPE']) ?? '';

                if(str_contains($contentType, 'application/json')):
                    $value = json_decode(file_get_contents("php://input"), true);

                    $value = $value ?? [];

                    if(is_array($value)):
                        foreach($value as $key => $val):

                            // Collect and clean the data gotten from the client
                            $value[$key] = Func::cleanData($val, 'string');
                        endforeach;
                    endif;

                    $this->data = $value;

                    $this->status = 1;
                    $this->message = "void";
                    $this->content = $value;

                else:
                    $this->status = 0;
                    $this->message = "void";
                    $this->content = "Content type should be application/json";

                endif;
                                    
                // Storing the allowed actions that a user can perform
                $this->save_route('post', $action, $callback);

            else:
                return false;

            endif;

            return $this->deliver();
        }

        public function is_get() : string|false {
            // Check if the request method is post
            if(isset($_GET) || strtoupper($_SERVER['REQUEST_METHOD']) === "GET"):
                $value = explode("&", $_SERVER['QUERY_STRING']);
                //$value = explode("=", $value);
                
                $this->data = Func::cleanData($value, 'string');

                $this->status = 1;
                $this->message = "void";
                $this->content = $this->data;
            else:
                return false;
            endif;

            return $this->deliver();
        }
        

        public function listen() {
            $req = strtolower(Func::cleanData($_SERVER['REQUEST_METHOD'], 'string'));

            $data = $this->data;

            $requestedAction = $this->actions[$req][$data['part']] ?? null;

            if(is_callable($requestedAction) && is_string($requestedAction)):
                call_user_func($requestedAction);

                return;
            endif;

            if(is_iterable($requestedAction)):
                [$class, $method] = $requestedAction;
                return (new $class(self::$db))->$method($this->data);
                
            endif;

            $this->status = 0;
            $this->message = "void";
            $this->content = "Invalid part provided";

            return $this->deliver();
        }
    }
?>