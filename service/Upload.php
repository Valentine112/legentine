<?php
    namespace Service;

    class Upload extends Response {

        public function __construct(string $folder, string $path, array $files)
        {
            // This is the path where the file goes
            $this->folder = Func::cleanData($folder, 'string');
            // Picture directory to access from site
            $this->path = Func::cleanData($path, 'string');
            $this->files = $files;
        }

        public function createFolder() : array {
            $this->status = 0;
            $this->type = "error";

            /**
             * Check if folder exist
             * If it doesn't, create a new one
             * Else work with the already created one
             */

            if(!is_dir($this->folder)){
                if(mkdir($this->folder, 0777)):
                    $this->status = 1;
                    $this->message = "void";
                    $this->content = "Successful";

                else:
                    $this->status = 0;
                    $this->message = "void";
                    $this->content = "Failed to create folder";

                endif;
            }else{
                $this->status = 1;
                $this->message = "void";
                $this->content = "Successful";
            }

            return $this->deliver();
        }

        public function editImage(string $photo_name) : string {

            $date = Date("y_m_d");
            $rand = random_int(0, 9999);

            if(strpos($photo_name, "%") >= 0):
                $photo_name = str_replace("%", "_", $photo_name);
            endif;
            if(strpos($photo_name, "LT") === FALSE):
                $photo_name = "LT".$photo_name;
            endif;

            return $photo_name."$rand $date";
        }

        public function saveImage() : array {
            $paths = [];
            $this->type = "error";

            // Check if folder exists
            $create_folder = $this->createFolder();
            if($create_folder['status'] === 1):

                $valid_ext = ["png", "jpg", "jpeg"];

                (array) $name = $this->files['name'];
                $fileCount = count($name);

                for($i = 0; $i < $fileCount; $i++):
                    $name = $this->files['name'][$i];
                    $size = $this->files['size'][$i];
                    $tmp_name = $this->files['tmp_name'][$i];

                    $ext_name = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $photo_name = pathinfo($name, PATHINFO_FILENAME);

                    if(!in_array($ext_name, $valid_ext)):
                        $this->status = 0;
                        $this->message = "fill";
                        $this->content = "Extension should be either one of these $valid_ext";

                    elseif($size > 5000000):
                        $this->status = 0;
                        $this->message = "fill";
                        $this->content = "File size shouldn't be greater than 5 megebytes";

                    else:
                        $edited = $this->editImage($photo_name);
                        
                        $folderPath = $this->folder."/".$edited.".".$ext_name;
                        $photoPath = $this->path."/".$edited.".".$ext_name;

                        if(move_uploaded_file($tmp_name, $folderPath)):
                            array_push($paths, $photoPath);
                    
                            $this->type = "success";
                            $this->status = 1;
                            $this->message = "void";

                        else:
                            array_push($paths, 0);

                            $this->status = 0;
                            $this->message = "void";
                            break;
                        endif;

                        $this->content = $paths;
                    endif;
                endfor;
            else:
                return $create_folder;

            endif;

            return $this->deliver();
        }

    }

?>