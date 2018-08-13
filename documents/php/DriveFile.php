<?php

class DriveFile{
    
    private $fileTypes = [
            "pdf" => "pdf", 
            "txt" => "text", 
            "docx" => "word", 
            "xlsx" => "excel", 
            "ppt" => "powerpoint", 
            "pptx" => "powerpoint", 
            "key" => "keynote", 
            "numbers" => "numbers", 
            "pages" => "pages",
            "png" => "png",
            "jpg" => "jpg",
            "gif" => "gif"
        ];
    
    public function getImageLocation($fileName = "default"){
        $url = "img/file_icons/";
        if(isset($this->fileTypes[$fileName])){
            $url .= $this->fileTypes[$fileName];
        }else{
            $url .= "default";
        }
        $url .= ".png";
        return $url;
    }
    
    public function newFile($file){
        //creates a folder with a time stamp
        $timestamp = time();
        if(strcmp("C:/xampp/htdocs", $_SERVER['DOCUMENT_ROOT']) == 0){
            $target_dir = "C:/xampp/htdocs/main/files/$timestamp/";
        }else{
            $target_dir = "/var/www/i-nucleus.com/main/files/$timestamp/";
        }
        
        mkdir($target_dir);

        $target_file = $target_dir . basename($file["name"]);
       
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "www.i-nucleus.com/main/files/$timestamp/";
        } else {
            return false;
        }
        
    }
    
    public function getExtension($fileName){
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }
}