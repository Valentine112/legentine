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
            if(strtoupper($_SERVER["REQUEST_METHOD"]) === "POST"):
                // Make sure Content-type is application/json
                $contentType = ($_SERVER['CONTENT_TYPE']) ?? '';

                $value = json_decode(file_get_contents("php://input"), true);

                if(str_contains($contentType, 'application/json')):
                    // Check if the data is json
                    if(json_last_error() === JSON_ERROR_NONE):

                        $value = $value ?? [];

                        if(is_array($value)):
                            array_walk_recursive($value, function(&$val, $key) {
                                $val = Func::cleanData($val, 'string');
                            });

                        else:
                            $value = Func::cleanData($value, 'string');
                        endif;
                    else:
                        // Data should be json since the header type is json
                        $this->status = 0;
                        $this->message = "void";
                        $this->content = "Data should be json since the header type is json";
                    endif;

                    $this->data = $value;

                    $this->status = 1;
                    $this->message = "void";
                    $this->content = $value;

                else:
                    $data = [];

                    // Check if it's not a json data
                    if(json_last_error() !== JSON_ERROR_NONE):
                        // Check if it's a file
                        if($_POST['type'] === "file"):

                            // Clean the routing request variables
                            foreach($_POST as $key => $val):
                                $data[$key] = Func::cleanData($val, 'string');
                            endforeach;

                            // Clean the file
                            array_walk_recursive($_FILES, function(&$val, $key) {
                                $val = Func::cleanData($val, 'string');
                            });

                            $data['val'] = [
                                "files" => $_FILES['files'],
                                "type" => $data['uploadType'],
                                "mode" => $data['mode'],
                            ];

                        endif;

                        $this->data = $data;

                    endif;
                endif;
                                    
                // Storing the allowed actions that a user can perform
                $this->save_route('post', $action, $callback);

            else:
                return false;

            endif;

            return $this->deliver();
        }

        public function is_get(string $action, callable|array $callback) : array|false {
            if(strtoupper($_SERVER['REQUEST_METHOD']) === "GET"):
                foreach($_GET as $ind => $val):
                    $_GET[$ind] = Func::cleanData($val, 'string');
                endforeach;

                $this->data = $_GET;
            endif;

            $this->save_route('get', $action, $callback);

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