<?php

    namespace Model;

    use Service\Mailing;
    use Service\Func;
    use Service\Response;
    use Service\FileHandling;

    class EmailValidation extends Response {

        public function __construct(string $email, ?string $name, array $more) {
            $this->path = "../log/session.json";

            $this->email = Func::cleanData($email, 'string');
            $name == null ? $this->name = "" : $this->name = Func::cleanData($name, 'string');

            $this->more = $more;
        }

        public function process() : array {
            $code = (int) random_int(100000, 999999);
            $token = Func::tokenGenerator();
            $config = Func::email_config();

            $mailing = new Mailing($this->email, $this->name, $code, $config);
            $mailing->set_params("<h1>Hello there </h1>", "Confirm email Address");
            if($mailing->send_mail()):
                $file = new FileHandling($this->path);
                $data = json_decode($file->fetchFile(), true);

                $data[$token]['code'] = $code;
                $data[$token]['time'] = time();
                $data[$token]['content'] = $this->more;

                if(is_int($file->writeFile($data))):
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = "Success";

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