<?php

    namespace Service;

    use Service\{
        Mailing,
        Func,
        Response,
        FileHandling
    };

    class EmailValidation extends Response {

        public function __construct(string $email, ?string $name, array $more) {
            $this->path = "log/session.json";

            $this->email = Func::cleanData($email, 'string');
            $name == null ? $this->name = "" : $this->name = Func::cleanData($name, 'string');

            $this->more = $more;

        }

        public function main() : array {
            $this->type = "EmailValidation/main";

            $code = (int) random_int(100000, 999999);
            $token = Func::tokenGenerator();
            $config = Func::email_config();

            // Sent an email address with the security code along
            $mailing = new Mailing($this->email, $this->name, $code, $config);
            $mailing->set_params("<h1>Hello there </h1>", "Confirm email Address");

            // Changed the condition to force so i could test for other parts
            // Would need to turn the condition to true for the correct working

            if(!$mailing->send_mail()):

                // Fetched the contents of the session.json file
                $file = new FileHandling($this->path);
                $data = json_decode($file->fetchFile(), true);

                $data[$token]['code'] = $code;
                $data[$token]['time'] = time();
                $data[$token]['content'] = $this->more;

                // Writing the user details and token to the file
                if(is_int($file->writeFile($data))):
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = [
                        'key' => $token
                    ];

                else:
                    $this->status = 0;
                    $this->message = "void";
                    $this->content = "Failed to write file";
                    
                endif;

            else:
                $this->status = 0;
                $this->message = "void";
                $this->content = "Failed to Send email";

            endif;

            return $this->deliver();
        }
        
    }