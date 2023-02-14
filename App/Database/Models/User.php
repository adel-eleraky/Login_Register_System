<?php
namespace App\Database\Models;

use App\Database\Config\Connection;

include_once "App/Database/Config/Connection.php";

class User extends Connection {

    private $id , $name , $phone ,$email , $password , $verification_code , $email_verified_at ,$image;

    // create user into database
    public function Create(){
        $query = "INSERT INTO users (name , phone , email , password , verification_code) VALUES (? , ? , ? , ? , ?)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('sissi' , $this->name , $this->phone , $this->email , $this->password , $this->verification_code);
        return $statement->execute();
    }

    // check verification code
    public function checkCode(){
        $query = "SELECT * FROM users WHERE email = ? AND verification_code = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('si' , $this->email ,$this->verification_code);
        $statement->execute();
        return $statement->get_result()->num_rows;
    }

    // check if the user is verified
    public function verify(){
        $query = "UPDATE users SET email_verified_at = ? WHERE email = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('ss'  , $this->email_verified_at , $this->email);
        return $statement->execute();
        
    }

    // get user by email 
    public function getUserByEmail(string $email){
        $query = "SELECT * FROM users WHERE email = ? ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s' , $email);
        $statement->execute();
        return $statement->get_result();
    }

    // update verification code
    public function UpdateCode(){
        $query = "UPDATE users SET verification_code = ? WHERE email = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('is' , $this->verification_code , $this->email);
        return $statement->execute();
    }

    // update password
    public function UpdatePassword(){
        $query = "UPDATE users SET password = ? WHERE email = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('ss' , $this->password , $this->email);
        return $statement->execute();
    }

    // check if password is correct
    public function CheckPassword(string $password , string $email){
        $query = "SELECT password FROM users WHERE email = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('s'  , $email);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        if(password_verify($password , $result['password'])){
            return true;
        }
        return false;
    }

    // update user's details 
    public function updateDetails(){
        $query = "UPDATE users SET name = ? , email = ? , phone = ? WHERE id = ?";
        $statement =$this->connection->prepare($query);
        $statement->bind_param('ssii' , $this->name , $this->email , $this->phone , $this->id );
        return $statement->execute();
    }

    // upload image to database
    public function uploadImage(){
        $query = "UPDATE users SET image = ? WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('si' , $this->image , $this->id);
        return $statement->execute();
    }
    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = password_hash($password , PASSWORD_BCRYPT);

        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of verification_code
     */ 
    public function getVerification_code()
    {
        return $this->verification_code;
    }

    /**
     * Set the value of verification_code
     *
     * @return  self
     */ 
    public function setVerification_code($verification_code)
    {
        $this->verification_code = $verification_code;

        return $this;
    }

    /**
     * Get the value of email_verified
     */ 
    public function getEmail_verified_at()
    {
        return $this->email_verified_at;
    }

    /**
     * Set the value of email_verified
     *
     * @return  self
     */ 
    public function setEmail_verified_at($email_verified_at)
    {
        $this->email_verified_at = $email_verified_at;

        return $this;
    }
}

?>