<?php
    namespace Controller;

    use mysqli;
    use Service\Response;
    use Config\Authenticate;

    use Model\Feature as ModelFeature;

    class Feature extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {
            define("USER", Authenticate::check_user());

            (array) $result = [];

            $modelFeature = new ModelFeature(self::$db, $data, USER['content']);
            
            // Logged in activities
            // Check if user is logged in
            if(USER['type'] === 2):

                switch ($data['action']):

                    case "fetchRequest":
                        $result = $modelFeature->fetchRequest(null);

                        break;

                    case "fetchHistory":
                        $result = $modelFeature->fetchHistory();

                        break;

                    case "request":
                        $result = $modelFeature->request();

                        break;

                    case "confirmFeature":
                        $result = $modelFeature->confirmFeature();

                        break;

                    default:
                    break;

                endswitch;
            else:
                $this->type = "warning";
                $this->status = 0;
                $this->message = "fill";
                $this->content = USER['content'];

                $result = $this->deliver();

            endif;

            return $result;
        }
    }

?>