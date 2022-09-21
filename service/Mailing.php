<?php
    namespace Service;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    //use PHPMailer\PHPMailer\Exception;
    use Service\Func;

    class Mailing extends PHPMailer{

        public function __construct(string $email, ?string $fullname, ?int $code, array $config) {
            //Instantiation and passing 'true' enables exceptions
            parent::__construct(false);

            $this->email = Func::cleanData($email, "email");
            $this->fullname = is_string($fullname) ? Func::cleanData($fullname, "string") : "";
            
            $this->code = $code !== null ? Func::cleanData($code, "integer") : 0;

            $this->config = $config;
        }

        public function config() {
            $id = $this->config['id'];
            $pass = $this->config['pass'];
            //Server settings
            $this->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
            $this->isSMTP();                                            // Send using SMTP
            $this->CharSet = "utf-8";
            $this->Host = $this->config['host'];                    // Set the SMTP server to send through
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
            // Server info
            $this->setFrom($this->config['id'], $this->config['name']);
            // Add a recipient
            $this->addAddress($this->email, $this->fullname);
            // Set email format to HTML
            $this->isHTML(true);
            // Set the subject of the mail
            $this->Subject = $subject;
            // Set the body of the mail
            $this->msgHTML($message);
            // Set an alternative body of the mail
            $this->AltBody = $this->code;
       
        }

        public function send_mail() : bool {
            return $this->send();
        }
    }
?>