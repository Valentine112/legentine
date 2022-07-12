<?php
    namespace Model;

    use mysqli;
    use Service\{
        Response,
        Func,
        EmailValidation,
    FileHandling
};

    use Query\{
        Insert,
        Select,
        Update
    };

    /**
     * Check if user exist and crosscheck the password to verify the user
     * If its not correct send an error message
     * If its correct, check if the user has logged in before
     * if he hasn't, log the person in without auth
     * If he has, check if the current device matches any of the device used for loggin in before
     * If it matches any, log the person in
     * If it doesn't proceed to the authentication
     * and verify the user via emailVerification
     */

    class Login extends Response {
        private static $db;

        public function __construct(mysqli $db, array $data) {

            $this->device = Func::deviceInfo()[0];
            $this->ip = Func::deviceInfo()[1];
            self::$db = $db;
            
            $this->selecting = new Select(self::$db);
            $this->data = $data['val'];
        }

        public function verify() : array {
            $this->type = "Model/Login/verify";

            $user = $this->data['user'];
            $password = $this->data['password'];

            // Check if the user exist
            $this->selecting->more_details("WHERE email = ? OR username = ?, $user, $user");
            $action = $this->selecting->action("email, user, password", "user");
            $this->selecting->reset();

            if($action != null) {
                return $action;
            }

            $value = $this->selecting->pull();

            if($value[1] < 1):
                $this->status = 0;
                $this->message = "fill";
                $this->content = "Invalid username or password";

            else:
                $user_email = $value[0][0]['email'];
                $user_id = $value[0][0]['user'];
                $user_password = $value[0][0]['password'];
                if(password_verify($password, $user_password)):
                    // Now check for device change
                    $data = [
                        "id" => $user_id,
                        "email" => $user_email
                    ];

                    $device_check = $this->check_device($data);

                    return $device_check;
                else:
                    $this->status = 0;
                    $this->message = "fill";
                    $this->content = "Invalid username or password";

                endif;

            endif;

            return $this->deliver();
        }

        public function check_device(array $data) : array {
            // Check if its the first time logging in
            // By checking if the id exist in the table
            // If it does, fetch all the devices registered under the id
            // And check if the current device used for loggin in matches any of them

            $user = $data['id'];
            $email = $data['email'];
            $token = Func::tokenGenerator();

            $this->selecting->more_details("WHERE user = ?, $user");
            $action = $this->selecting->action("token, device", "logins");
            $this->selecting->reset();

            if($action != null) {
                return $action;
            }

            $value = $this->selecting->pull();
            // First evaluation means that the id does not exist and user can login without auth

            if($value[1] < 1):
                // Proceed to add new device and login
                return $this->addNewDevice($user);

            else:
                $data = $value[0];
                $check = Func::searchObject($data, $this->device, 'device');

                if(in_array(1, $check)):

                    // Process by updating the token for the device
                    $updating = new Update(self::$db, "SET token = ? WHERE user = ?# $token# $user");
                    $action = $updating->mutate('si', 'logins');
                    if(is_bool($action) && $action):
                        $this->status = 1;
                        $this->message = "double/success";
                        $this->content = "Success";

                        $this->save_cookie($token);

                        return $this->deliver();
                    else:
                        return $action;
                    endif;

                else:
                    // Authenticate
                    $val = [
                        "email" => $email,
                        "user" => $user,
                        "device" => $this->device,
                        "ip" => $this->ip,
                        "remember" => $this->data['remember']
                    ];

                    $email_body = "<h1> Hello there </h1>";

                    $auth = new EmailValidation(LOGINFILE, $email, null, $val);
                    $auth = $auth->main(null, $email_body);

                    if($auth['status'] === 1):
                        // Create a double message type, where there can be status as 1 from 2 different results
                        // The content would be what defines how the message would be handled

                        $auth['message'] = "double/Auth";

                        return $auth;
                    else:
                        return $auth;
                    endif;

                endif;

            endif;

        }

        public function addNewDevice($user) {
            $token = Func::tokenGenerator();
            // Process by adding a new device and token
            $subject = [
                "user",
                "token",
                "device",
                "ip",
                "time"
            ];

            $item = [
                $user,
                $token,
                $this->device,
                $this->ip,
                time()
            ];

            $inserting = new Insert(self::$db, "logins", $subject, "");
            $action = $inserting->push($item, 'isssi');
            if(is_bool($action) && $action):
                $this->status = 1;
                $this->message = "double/success";
                $this->content = "Success";

                $this->save_cookie($token);

                return $this->deliver();
            else:
                return $action;

            endif;
        }

        public function save_cookie(string $token) {

            // Check if user choose to save the login details
            if($this->data['remember']):
                $exp = strtotime(' +14 days ');
            else:
                $exp = 0;
            endif;

            setcookie("token", $token, $exp, "/", "", FALSE, TRUE);
        }

        public function forgot() : array {
            // Fetch the email address and the user id from either the username or the email
            // Pass the email and userid as part of the data that would need to be stored in a file
            $user_form = $this->data['user'];

            // Check if the user exist
            $this->selecting->more_details("WHERE email = ? OR username = ?, $user_form, $user_form");
            $action = $this->selecting->action("user, email", "user");
            $this->selecting->reset();

            if($action != null) {
                return $action;
            }

            $value = $this->selecting->pull();
            
            if($value[1] > 0):

                $user = $value[0][0]['user'];
                $email = $value[0][0]['email'];

                $data = [
                    "user" => $user,
                    "email" => $email
                ];

                $email_body = "<h1> Hello there </h1>";

                $auth = new EmailValidation(LOGINFILE, $email, null, $data);
                $auth = $auth->main(null, $email_body);

                return $auth;
            else:
                $this->status = 0;
                $this->message = "fill";
                $this->content = "There is no user associated with the information provided";

            endif;

            return $this->deliver();
        }

        public function update_password() : array {
            // Check if the password meets the standard
            if(strlen(trim($this->data['password'])) < 7 || !preg_match("/[0-9]/", $this->data['password'])):
                $this->status = 0;
                $this->message = "Fill";
                $this->content = "Password should contain both letters and numbers and should be greater than 7";

            else:
                // Hash the password
                $password = password_hash($this->data['password'], PASSWORD_DEFAULT);

                $file = new FileHandling(LOGINFILE);
                $data = json_decode($file->fetchFile(), true);

                // Check if the key exist
                if(!empty($data[$this->data['token']])):

                    $user_data = $data[$this->data['token']];
                    $user = $user_data['content']['user'];

                    // Update the user password
                    $updating = new Update(self::$db, "SET password = ? WHERE user = ?# $password# $user");
                    $action = $updating->mutate('si', 'user');

                    if(is_bool($action) && $action):
                        $this->status = 1;
                        $this->message = "double/success";
                        $this->content = "Success";

                        // Delete the key from the file
                        $file->deleteKey($this->data['token']);
                    else:
                        return $action;
                    endif;

                else:
                    $this->status = 0;
                    $this->message = "void";
                    $this->content = "token not found in file";

                endif;
            endif;

            return $this->deliver();
        }
    }
?>