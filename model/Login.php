<?php
    namespace Model;

    use mysqli;
    use Service\{
        Response,
        Func,
        EmailValidation
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
                    $this->message = "double";
                    $this->content = "Success";

                    $this->save_cookie($token);

                    return $this->deliver();
                else:
                    return $action;

                endif;
            else:
                $data = $value[0];
                $check = Func::searchObject($data, $this->device, 'device');

                if(in_array(1, $check)):
                    // Process by updating the token for the device
                    $updating = new Update(self::$db, "SET token = ? WHERE user = ?# $token# $user");
                    $action = $updating->mutate('si', 'logins');
                    if(is_bool($action) && $action):
                        $this->status = 1;
                        $this->message = "double";
                        $this->content = "Success";

                        $this->save_cookie($token);

                        return $this->deliver();
                    else:
                        return $action;
                    endif;

                else:
                    // Authenticate
                    $val = [
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

                        $auth['message'] = "double";
                        $auth['content'] = "Auth";

                        return $auth;
                    else:
                        return $auth;
                    endif;

                endif;

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
    }
?>