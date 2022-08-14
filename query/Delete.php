<?php

    namespace Query;

    use Service\Response;

    class Delete extends Response {

        public $type;
        public $value = [];
        public $more1;

        public function __construct(object $conn, string $more) {
            $this->connect = $conn;
            $this->more = $more;
        }

        public function process(){
            $prepared = [];

            if($this->more != "" && strlen(trim($this->more)) > 0) {
                $more_split = explode(',', $this->more);
                $this->more1 = $more_split[0];
                $more_len = count($more_split);

                for($a = 0; $a < $more_len; $a++) {
                    if($a > 0) {
                        array_push($prepared, 's');
                        $more_value = $more_split[$a];
                        array_push($this->value, stripslashes(trim($more_value)));
                    }
                }
                $this->type = join($prepared);
            }
        }

        public function proceed(string $where) : bool|array {
            $this->process();

            $more_ = $this->more1;
            $deleting = $this->connect->prepare("DELETE FROM $where $more_");
            $more_split = explode(',', $this->more);
            if(count($more_split) > 1){
                $deleting->bind_param($this->type, ...$this->value);
            }
            if($deleting->execute()):
                return true;

            else:
                $this->type = "error";
                $this->status = 0;
                $this->message = "void";
                $this->content = "Failed to insert data";

                return $this->deliver();

            endif;
            
            $deleting->close();
        }

        public function end() {
            $this->value = [];
            $this->type = "";
        }

        public function close() : bool {
            return $this->connect->close();
        }
    }

?>