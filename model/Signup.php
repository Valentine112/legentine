<?php
    namespace Model;

    use mysqli;
    use Query\{
        Select,
        Insert
    };

    use Service\{
        EmailValidation,
        Response,
        FileHandling,
        Func
    };

    class Signup extends Response {
        private static $db;

        public function __construct(mysqli $db, array $data) {

            $this->data = $data;
            self::$db = $db;

            $this->selecting = new Select(self::$db);
            $this->file = new FileHandling("log/session.json");

            return $this;
        }

        public function verify() : array|bool {
            $process = $this->process();
            if($process['status'] === 1):
                $validate = new EmailValidation($this->data['email'], null, $this->data);

                return $validate->main(null);
            else:
                return $process;

            endif;
        }

        public function process() : array|bool {
            // Verify users input before moving forward
            // This method would be called under the method verify()

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
                $this->selecting->reset();

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

        public function confirm() : array {
            $this->check_code($this->data);

            if($this->status === 1):
                $this->type = "Model/Signup/save_user";
                unset($this->content['part']);
                unset($this->content['action']);
                
                $this->content['password'] = password_hash($this->content['password'], PASSWORD_DEFAULT);

                $subject = [
                    'token',
                    ...array_keys($this->content),
                    'photo',
                    'date',
                    'time'
                ];

                $items = [
                    Func::tokenGenerator(),
                    ...array_values($this->content),
                    'src/photo/image.jpg',
                    Func::dateFormat(),
                    time()
                ];

                // Save the user's info
                $inserting = new Insert(self::$db, "user", $subject, "");
                $action = $inserting->push($items, 'sssssssi');
                if(is_bool($action) && $action):
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = $this->content['username'];

                else:
                    return $action;
                endif;
            else:
                return $this->deliver();
            endif;

            return $this->deliver();
        }

        public function resend() : array {
            // Fetch the data from the session.json file
            $fetch = json_decode($this->file->fetchFile(), true);

            // Check if the token from the client exist in that data fetched
            if(isset($fetch[$this->data['token']])):
                $info = $fetch[$this->data['token']];

                $email = $info['content']['email'];
                if(!empty($email)):
                    $validate = new EmailValidation($email, null, $this->data);

                    return $validate->main($this->data['token']);

                else:
                    $this->status = 0;
                    $this->message = "void";
                    $this->content = "Email address was not found";

                endif;

            else:
                $this->status = 0;
                $this->message = "void";
                $this->content = "You've touched something, LOL";

            endif;

            return $this->deliver();
        }

        public function check_code(array $data) : array {
            $this->type = "Controller/Signup/check_code";

            $expiring = 5 * 60;

            // Fetch the data from the session.json file
            $fetch = json_decode($this->file->fetchFile(), true);

            // Check if the token from the client exist in that data fetched
            if(isset($fetch[$data['token']])):

                $info = $fetch[$data['token']];

                // Check the time time is greater than 5 minutes
                // If so, it has expired
                if(time() - Func::cleanData($info['time'], 'integer') >= $expiring):
                    $this->status = 0;
                    $this->message = "Fill";
                    $this->content = "Code has expired";
                
                // Confirm if the code matches the one that was sent
                elseif(Func::cleanData($info['code'], 'integer') != $data['code']):
                    $this->status = 0;
                    $this->message = "Fill";
                    $this->content = "Code is incorrect";

                else:
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = $info['content'];

                    unset($fetch[$this->data['token']]);
                    $this->file->writeFile(json_encode($fetch));

                endif;
            else:
                $this->status = 0;
                $this->message = "Fill";
                $this->content = "Please make sure you're using the same browser the code was sent from";

            endif;

            return $this->deliver();
        }
    }
?>