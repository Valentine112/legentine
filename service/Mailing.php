<?php
    namespace Service;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    //use PHPMailer\PHPMailer\Exception;
    use Service\Func;

    class Mailing extends PHPMailer{

        public function __construct(string $email, ?string $full_name, int $code, array $config) {
            //Instantiation and passing 'true' enables exceptions
            parent::__construct(false);

            $this->email = (string) Func::cleanData($email, "email");
            is_string($full_name) ? $this->full_name = Func::cleanData($full_name, "string") : $this->full_name = "";
            
            $this->code = (int) Func::cleanData($code, "integer");
            $this->config = $config;
        }

        public function config() {
            $id = $this->config['id'];
            $pass = $this->config['pass'];
            //Server settings
            $this->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $this->isSMTP();                                            // Send using SMTP
            $this->CharSet = "utf-8";
            $this->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $this->SMTPAuth   = true;                                   // Enable SMTP authentication
            $this->Username   = $id;                     // SMTP username
            $this->Password   = $pass;                               // SMTP password
            $this->SMTPSecure = 'TLS';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $this->Port       =  587;                                    // TCP port to connect to
            $this->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }

        public function set_params(string $message, string $subject) {
            $this->config();
            //Recipients
            $this->setFrom($this->config['id'], $this->config['name']);
            $this->addAddress($this->email, $this->full_name);     // Add a recipient

            $this->isHTML(true);                                  // Set email format to HTML
            $this->Subject = $subject;

            $this->msgHTML($message);
            
            $this->AltBody = $this->code;
       
        }

        public function send_mail() : bool {
            return $this->send();
        }
    }
?>