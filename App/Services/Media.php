<?php 

namespace App\Services;

class Media {

    private array $file = [];
    private string $newMediaName ;
    

    // upload file from temp location to new location on the server 
    public function upload(string $path){

        $this->newMediaName = uniqid() . "." . $this->getFileExtensions();
        return move_uploaded_file($this->file['tmp_name']  ,  $path . $this->newMediaName);
    }


    public function getFileExtensions(){

        return explode("/" , $this->file['type'])[1];
    }

    // delete file from the server
    public function Delete(string $file){
        if(file_exists($file)){
            return unlink($file);
        }
        return $this;
    }
    /**
     * Get the value of file
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get the value of newMediaName
     */ 
    public function getNewMediaName()
    {
        return $this->newMediaName;
    }

    /**
     * Set the value of newMediaName
     *
     * @return  self
     */ 
    public function setNewMediaName($newMediaName)
    {
        $this->newMediaName = $newMediaName;

        return $this;
    }
}





?>