<?php
    namespace Controller;

    use mysqli;
    use Service\{
        Response,
        Func
    };
    use Model\User as ModelUser;
    use Config\Authenticate;

    class User extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {

            define("USER", Authenticate::check_user());

            $modelUser = new ModelUser(self::$db, $data, USER['content']);

            (array) $result = [];

            if(USER['type'] === 2):

                switch($data['action']):

                    case 'userIdentification':
                        
                        $this->type = "success";
                        $this->status = 1;
                        $this->message = "void";
                        $this->content = USER['content'];

                        $result = $this->deliver();

                        break;

                    case 'fetchUnlisted':
                        $result = $modelUser->fetchUnlisted();

                        break;

                    case 'unlist':
                        $result = $modelUser->unlist();

                        break;

                    case 'openSearch';
                        $result = $modelUser->openSearch();
                    
                        break;

                    case 'search':
                        $result = $modelUser->search();

                        break;

                    case 'fetchUser':
                        $result = $modelUser->fetchUser();

                        break;

                    case 'rateUser':
                        $result = $modelUser->rateUser();

                        break;

                    case 'pin':
                        $result = $modelUser->pin();

                        break;

                    case "uploadPhoto":
                        $result = $modelUser->uploadPhoto();

                        break;

                    case "fetchPhotos":
                        $result = $modelUser->fetchPhotos();

                        break;

                    case "deleteImage";
                        $result = $modelUser->deleteImage();

                        break;

                    case "changeQuote":
                        $result = $modelUser->changeQuote();

                        break;

                    case "changeUsername":
                        $result = $modelUser->changeUsername();

                        break;

                    case "changePassword":
                        $result = $modelUser->changePassword();

                        break;

                    case "fetchPin":
                        $result = $modelUser->fetchPin();

                        break;

                    case "sendFeedback":
                        $result = $modelUser->sendFeedback();

                        break;
                    
                    default:

                        break;

                endswitch;

            else:
                $this->type = "warning";
                $this->status = 0;
                $this->message = "fill";
                $this->content = USER['content'];

                $result = $this->deliver();

            endif;

            return $result;
        }

    }
?>