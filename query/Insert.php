<?php

    namespace Query;

    use Service\Response;

    class Insert extends Response {

        static $ques = [];
        static $insert_item = "";

        public function __construct(object $conn, string $where, array $subject, string $more) {
            $this->connect = $conn;
            $this->where = $where;
            $this->subject = $subject;
            $this->more = $more;

            $this->subject_len = count($subject);
        }

        public function create_ques() {
            for($a = 0; $a < $this->subject_len; $a++):
                $sum = $a + 1;
                if($sum < $this->subject_len):
                    array_push(self::$ques, "?,");
                elseif($sum === $this->subject_len):
                    array_push(self::$ques, "?");
                endif;
            endfor;
        }

        public function push(array $values, string $type) : array|bool {
            $this->create_ques();
            
            $item = implode(",", $this->subject);
            $prepared = join(self::$ques);

            self::$insert_item = $this->connect->prepare("INSERT INTO $this->where ($item) VALUES ($prepared) $this->more");
            self::$insert_item->bind_param($type, ...$values);
            if(self::$insert_item->execute()){
                return true;
            }else{
                $this->status = 0;
                $this->message = "void";
                $this->content = "Failed to insert data";

            }
            self::$insert_item->close();

            return $this->deliver();
        }

        public function reset() {
            self::$ques = [];
            //$this->subject = [];
        }
    }

?>