<?php

    namespace QUERY;

    class Delete {

        public $type;
        public $value = [];
        public $more1;
        public $prepared = [];

        public function __construct(object $conn, string $more) {
            $this->connect = $conn;
            $this->more = $more;
        }

        public function process(){
            if($this->more != "" && strlen(trim($this->more)) > 0) {
                $more_split = explode(',', $this->more);
                $this->more1 = $more_split[0];
                $more_len = count($more_split);

                for($a = 0; $a < $more_len; $a++) {
                    if($a > 0) {
                        array_push($this->prepared, 's');
                        $more_value = $more_split[$a];
                        array_push($this->value, stripslashes(trim($more_value)));
                    }
                }
                $this->type = join($this->prepared);
            }
        }

        public function proceed(string $where) : bool {
            $this->process();

            $more_ = $this->more1;
            $deleting = $this->connect->prepare("DELETE FROM $where $more_");
            $more_split = explode(',', $this->more);
            if(count($more_split) > 1){
                $deleting->bind_param($this->type, ...$this->value);
            }
            return $deleting->execute();
            $deleting->close();
        }

        public function end() {
            unset($this->value);
        }

        public function close() : bool {
            return $this->connect->close();
        }
    }

?>