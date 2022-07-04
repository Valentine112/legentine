<?php

    namespace Query;

    class Insert extends Select {

        static $ques = [];
        static $insert_item = "";

        public function __construct(object $conn, string $where, array $items, string $more) {
            $this->connect = $conn;
            $this->where = $where;
            $this->items = $items;
            $this->more = $more;

            $this->items_len = count($items);
        }

        public function create_ques() {
            for($a = 0; $a < $this->items_len; $a++):
                $sum = $a + 1;
                if($sum < $this->items_len):
                    array_push(self::$ques, "?,");
                elseif($sum === $this->items_len):
                    array_push(self::$ques, "?");
                endif;
            endfor;
        }

        public function push(array $values, string $type) : bool {
            $this->create_ques();
            
            $item = implode(",", $this->items);
            $prepared = join(self::$ques);

            self::$insert_item = $this->connect->prepare("INSERT INTO $this->where ($item) VALUES ($prepared) $this->more");
            self::$insert_item->bind_param($type, ...$values);
            if(self::$insert_item->execute()){
                return true;
            }else{
                return false;
            }
            self::$insert_item->close();
        }

        public function reset() {
            self::$ques = [];
            //$this->items = [];
        }
    }

?>