<?php

    namespace Query;

      use Service\Response;
      use mysqli;
      
      class Update extends Response{

            public $connect;
            public $value = [];
            public $more1 = "";
            public $more;

            public function __construct(object $conn, string $more) {
                  $this->connect = $conn;
                  $this->more = $more;
            }

            public function process() {
                  if($this->more != "" && strlen(trim($this->more)) > 0) {
                  $more_split = explode('#', $this->more);
                  $this->more1 = $more_split[0];
                  $more_len = count($more_split);

                  for($a = 0; $a < $more_len; $a++) {
                        if($a > 0) {
                              $more_value = $more_split[$a];
                              array_push($this->value, stripslashes(trim($more_value)));
                        }
                  }
                  }
            }

            public function mutate(string $type, string $where) : bool {
                  $this->process();
                  
                  $more_ = $this->more1;
                  $update = $this->connect->prepare("UPDATE $where $more_");
                  $more_split = explode('#', $this->more);
                  if(count($more_split) > 1){
                  $update->bind_param($type, ...$this->value);
                  }
                  if($update->execute()){
                  return true;
                  }else{
                  $this->type = "error";
                  $this->status = 0;
                  $this->message = "void";
                  $this->content = "Failed to update data";
                  $this->more = "lastActive";

                  return $this->deliver();
                  }
                  
                  $update->close();
            }

            public function close() : bool {
                  return $this->connect->close();
            }

      }

?>