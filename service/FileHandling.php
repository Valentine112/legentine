<?php
    namespace Service;

    class FileHandling {

        private string $file;
        private string $path;

        public function __construct(string $path) {
            $this->path = $path;

            return;
        }

        public function fetchFile() : string|false {
            $init = [];

            if(is_file($this->path)):
                $content = file_get_contents($this->path);
                empty($content) ? file_put_contents($this->path, json_encode($init)) : null;

            else:
                file_put_contents($this->path, json_encode($init));
            endif;

            $this->file = file_get_contents($this->path);

            return $this->file;
        }

        public function writeFile(string|array $data) : int|false {
            
            return file_put_contents($this->path, $data);
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