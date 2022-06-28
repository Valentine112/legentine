<?php
    namespace Service;

    class FileHandling {

        private string $file; 

        public function __construct(string $path) {
            $this->path = $path;
        }

        public function fetchFile() : resource|false {
            $init = "[]";

            if(is_file($this->path)):
                $content = file_get_contents($this->path);
                (empty($content)) ?? file_put_contents($this->path, json_encode($init));
            else:
                file_put_contents($this->path, json_encode($init));
            endif;

            $this->file = file_get_contents($this->path);

            return $this->file;
        }

        public function writeFile(string|array $data) : int|false {
            $content = json_decode($this->file, true);
            array_push($content, $data);
            return file_put_contents($this->path, json_encode($content));
        }

        public function deleteKey(string $key) : int|false {
            $content = json_decode($this->file, true);
            unset($content[$key]);
            return file_put_contents($this->path, json_encode($content));
        }
        
        public function closeFile() : bool {
            return fclose($this->path);
        }
    }
?>