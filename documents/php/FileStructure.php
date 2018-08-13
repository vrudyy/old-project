<?php

class FileStructure{

    public static function newClient($username){
        $name = strtolower($username);
        mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name");
        mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/img");
        mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/contacts");
        mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/contacts/vcards");
        self::newFilesFolder($username);
    }
    
    public static function newFilesFolder($username){
        $name = strtolower($username);
        if(!file_exists($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/files")){
            mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/files");
            mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/files/teachingnotes");
            mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/files/tests");
            mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/files/revisionnotes");
            mkdir($_SERVER['DOCUMENT_ROOT']."/main/clients/$name/files/revisionexercises"); 
        }
    }
}

