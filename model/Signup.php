<?php
    namespace Model;

    use Service\Response;
    use Query\Select;
    use mysqli;
use Service\EmailValidation;

    class Signup extends Response {
        private static $db;

        public function __construct(mysqli $db, array $data) {
            unset($data['action']);

            $this->data = $data;
            self::$db = $db;

            $this->selecting = new Select(self::$db);

            return $this;
        }

        public function main() : array|bool {
            $process = $this->process();
            if($process['status'] === 1):
                $validate = new EmailValidation($this->data['email'], null, $this->data);

                return $validate->main();
            else:
                return $process;

            endif;
        }

        public function process() : array|bool {
            $this->type = "Model/Signup/Process";

            // Check if fullname has anyother character other than letter and space
            if(!preg_match("/^[a-z A-z]*$/", $this->data['fullname'])):
                $this->status = 0;
                $this->message = "Fill";
                $this->content = "Fullname should only have letters and space";

            // Check if username only has letters, numbers and underscore
            elseif(!preg_match("/^[a-z A-z 0-9]*$/", $this->data['username'])):
                $this->status = 0;
                $this->message = "Fill";
                $this->content = "Username only accepts letters,numbers and underscore";

            // Check if its a valid email address using php inbuilt function and constant
            elseif(!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)):
                $this->status = 0;
                $this->message = "Fill";
                $this->content = "Email address should be in format john****@****.com";

            // Check if password is greater than 6 and has atleast 1 integer and 1 symbol

            elseif(strlen(trim($this->data['password'])) < 7 || !preg_match("/[0-9]/", $this->data['password'])):
                $this->status = 0;
                $this->message = "Fill";
                $this->content = "Password should contain both letters and numbers and should be greater than 7";

            else:
                $email = $this->data['email'];

                $this->selecting->more_details("WHERE email = ?, $email");
                $action = $this->selecting->action("email", "user");
                //$this->selecting->reset();

                if($action != null) {
                    return $action;
                }

                $value = $this->selecting->pull();
                if($value[1] > 0):
                    $this->status = 0;
                    $this->message = "Fill";
                    $this->content = "Email already exist";

                else:
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = "Success";

                endif;
                
                return $this->deliver();

            endif;
        }
    }
?>