<?php

    namespace Service;

    use Service\{
        Mailing,
        Func,
        Response,
        FileHandling
    };

    class EmailValidation extends Response {

        public function __construct(string $path, string $email, ?string $name, array $more) {
            $this->path = $path;

            $this->email = Func::cleanData($email, 'string');
            $name == null ? $this->name = "" : $this->name = Func::cleanData($name, 'string');

            $this->more = $more;

        }

        public function main(?string $action, string $email_body) : array {
            $this->type = "EmailValidation/main";

            $code = (int) random_int(10000, 99999);
            // Comparison here to check if we're updating user validation or creating new user validation
            $token = ($action) ?? Func::tokenGenerator();
            $config = Func::email_config();

            // Fetched the contents of the session.json file
            $file = new FileHandling($this->path);
            $data = json_decode($file->fetchFile(), true);

            $data[$token]['code'] = $code;
            $data[$token]['time'] = time();

            ($action) ?? $data[$token]['content'] = $this->more;

            // Writing the user details and token to the file
            if(is_int($file->writeFile(json_encode($data)))):

                // Sent an email address with the security code along
                $mailing = new Mailing($this->email, $this->name, $code, $config);
                $mailing->set_params($email_body, "Confirm email Address");

                // Changed the condition to force so i could test for other parts
                // Would need to turn the condition to true for the correct working
                if(!$mailing->send_mail()):
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = [
                        'key' => $token
                    ];

                else:
                    $this->status = 0;
                    $this->message = "void";
                    $this->content = "Failed to Send email";

                endif;

            else:
                $this->status = 0;
                $this->message = "void";
                $this->content = "Failed to write file";
                
            endif;

            return $this->deliver();
        }
        
    }