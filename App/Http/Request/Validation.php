<?php  
namespace App\Http\Request;

use App\Database\Config\Connection;

include_once "App/Database/Config/Connection.php";


class Validation extends Connection {
    private $inputName , $input;
    private array $errors = [];
    private array $file = [];

    // check if the input is empty
    public function Required() : self{
        if(empty($this->input)){
            $this->errors[$this->inputName][__FUNCTION__] = "{$this->inputName} is Required";
        }
        return $this;
    }

    // check if the input is string
    public function String() : self{
        if(! is_string($this->input)){
            $this->errors[$this->inputName][__FUNCTION__] = "{$this->inputName} must be string";
        }
        return $this;
    }

    // check if the input is number
    public function Numeric() : self{
        if(! is_numeric($this->input)){
            $this->errors[$this->inputName][__FUNCTION__] = "{$this->inputName} must be number";
        }
        return $this;
    }

    // check the length of the input
    public function Between(int $min , int $max) : self{
        if(strlen($this->input) < $min | strlen($this->input) > $max){
            $this->errors[$this->inputName][__FUNCTION__] = "{$this->inputName} Length must be between {$min} , {$max} ";
        }
        return $this;
    }

    // check the length of the input
    public function Regex(string $pattern , string $message = null) : self{
        if(! preg_match($pattern , $this->input)){
            $this->errors[$this->inputName][__FUNCTION__] = $message ?? "{$this->inputName} isn't valid";
        }
        return $this;
    }

    // check if the input is unique
    public function Unique(string $table , string $column) : self{
        $query = "SELECT * FROM {$table} WHERE {$column} = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s' , $this->input);
        $statement->execute();
        if($statement->get_result()->num_rows == 1){
            $this->errors[$this->inputName][__FUNCTION__] = "{$column} must be unique";
        }
        return $this;
    }

    // check if the password is confirmed
    public function Confirmed(string $password) : self{
        if($this->input != $password){
            $this->errors[$this->inputName][__FUNCTION__] = "password doesn't match";
        }
        return $this;
    }

    // check if the user's email exist in database
    public function Exist(string $email) : self {
        $query = "SELECT * FROM users WHERE email = ? ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s' , $email);
        $statement->execute();
        if($statement->get_result()->num_rows != 1){
            $this->errors[$this->inputName][__FUNCTION__] = "{$this->inputName} Doesn't Exist in Database";
        }
        return $this;
    }

    // check the size of file
    public function size(int $size) : self {
        if($this->file['size'] > $size ){
            $this->errors[$this->inputName][__FUNCTION__] = "MAX Allowed Size Is ONE Mega";
        }
        return $this;
    }

    // check extensions of file
    public function Extensions(array $allowedValues) : self {

        $fileExtension = explode('/' , $this->file['type'])[1];
        if(! in_array($fileExtension , $allowedValues)){
            $this->errors[$this->inputName][__FUNCTION__] = "Allowed Extensions" . implode("," , $allowedValues);
        }
        return $this;
    }

    public function getErrorMessage(string $name) : ?string {

        if(isset($this->errors[$name])){
            foreach($this->errors[$name] as $error){
                return "<p style='color: red; font-size: 20px; margin-bottom: 20px' > ". $error ." </p>";
            }
        }
        return null;
    }

    /**
     * Get the value of inputName
     */ 
    public function getInputName()
    {
        return $this->inputName;
    }

    /**
     * Set the value of inputName
     *
     * @return  self
     */ 
    public function setInputName($inputName)
    {
        $this->inputName = $inputName;

        return $this;
    }

    /**
     * Get the value of input
     */ 
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set the value of input
     *
     * @return  self
     */ 
    public function setInput($input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Get the value of errors
     */ 
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set the value of errors
     *
     * @return  self
     */ 
    public function setErrors($errors)
    {
        $this->errors = $errors;

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
}

?>