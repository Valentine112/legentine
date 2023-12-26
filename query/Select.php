<?php
    namespace Query;

    use Service\Response;

    class Select extends Response {

        public $types;
        public $value = [];
        public $more = "";
        public $more1;
        public $prepared = [];
        public $result;
        public $connect;

        public function __construct(object $conn) {
            $this->connect = $conn;
        }

        public function more_details(string $more) {
            $this->more = $more;
        }

        public function process() {
            if($this->more != "" && strlen(trim($this->more)) > 0) {
                $more_split = explode('#', $this->more);
                $this->more1 = $more_split[0];
                $more_len = count($more_split);

                for($a = 0; $a < $more_len; $a++) {
                    if($a > 0) {
                        array_push($this->prepared, 's');
                        $more_value = $more_split[$a];
                        array_push($this->value, stripslashes(trim($more_value)));
                    }
                }
                $this->types = join($this->prepared);
            }
        }

        public function action(string $select_what, string $where) : ?array {
            $this->process();

            $more_split = explode('#', $this->more);
            $more_ = $this->more1;

            $value = [];
            $confirm = $this->connect->prepare("SELECT $select_what FROM $where $more_");
            if(count($more_split) > 1) {
                $confirm->bind_param($this->types, ...$this->value);
            }

            $response = $confirm->execute();

            $a = $confirm->get_result();
            $value = $a->fetch_all(MYSQLI_ASSOC);

            $this->result = [$value, count($value)];
            $confirm->close();

            if(!$response):
                $this->type = "error";
                $this->status = 0;
                $this->message = "void";
                $this->content = "Failed to fetch content";
                
                return $this->deliver();
                
            endif;

            return null;
        }

        public function pull() : array {
            return $this->result;
        }

        public function reset() {
            $this->value = [];
            $this->prepared = [];
            $this->types = "";
            $this->more = "";
            $this->more1 = "";
        }

    }

?>