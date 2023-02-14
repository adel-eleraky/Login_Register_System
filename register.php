<?php 
session_start();
use App\Database\Models\User;
use App\Mail\verificationCode;
use App\Http\Request\Validation;

include "App/Http/Middlewares/guest.php";
include "App/Database/Models/User.php";
include "App/Http/Request/Validation.php";
include "App/Mail/verificationCode.php";

$user = new User;
$validation = new Validation;

if($_SERVER['REQUEST_METHOD'] == "POST"){

    // make input validation
    $validation->setInputName('name')->setInput($_POST['name'] ?? "")->Required()->String()->Between(2 , 32);
    $validation->setInputName('phone')->setInput($_POST['phone'] ?? "")->Required()->Numeric()->Regex('/^01[0125][0-9]{8}$/' , "phone must be 11 number")->Unique("users" , "phone");
    $validation->setInputName('email')->setInput($_POST['email'] ?? "")->Required()->Regex('/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/')->Unique("users" , "email");
    $validation->setInputName('password')->setInput($_POST['password'] ?? "")->Required()->Regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/' , "Minimum eight and maximum 32 characters, at least one uppercase letter, one lowercase letter, one number and one special character");
    $validation->setInputName('password_confirmation')->setInput($_POST['password_confirmation'] ?? "")->Required()->Confirmed($_POST['password']);
    
    if(empty($validation->getErrors())){
        $code = rand(10000 , 99999); // verification code
        $user->setName($_POST['name'] ?? "")->setEmail($_POST['email'] ?? "")->setPhone($_POST['phone'] ?? "")->setPassword($_POST['password'] ?? "")->setVerification_code($code);
        // create user in database
        if($user->Create()){
            // send verification code to user's email
            $verificationMail = new verificationCode($_POST['email'] , "verification code" , "Your Verification Code : {$code}");
            if($verificationMail->send()){
                // redirect user to verification-code page
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['page'] = "register";
                header('location:verification-code.php');
            }
        }else{
            // display message if user creation failed
            $message  = "<div style='color:red; font-size:25px; margin-bottom:10px'>Something Went Wrong</div>";
        }
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style/master.css">
    <title>SYSTEM</title>
</head>
<body>
    <div class="header">
        <div class="container">
            <span><a href="index.php">LOGIN & REGISTER SYSTEM</a></span>
            <div class="nav-items">
                <ul>
                    <li><a href="login.php">LOGIN</a></li>
                    <li><a href="register.php">REGISTER</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="container">
            <div class="card">
                <?= $message ?? "" ?>
                <h3>LOGIN & REGISTER SYSTEM</h3>
                <form action="register.php" method="POST">
                    <label for="name">name</label>
                    <input type="text" name="name" id="name" placeholder="enter your name" value="<?= $_POST['name'] ?? "" ?>">
                    <?= $validation->getErrorMessage("name") ?? "" ?>
                    <label for="phone">phone</label>
                    <input type="number" name="phone" id="phone" placeholder="enter your phone" value="<?= $_POST['phone'] ?? "" ?>">
                    <?= $validation->getErrorMessage("phone") ?? "" ?>
                    <label for="email">email</label>
                    <input type="email" name="email" id="email" placeholder="enter your email" value="<?= $_POST['email'] ?? "" ?>">
                    <?= $validation->getErrorMessage("email") ?? "" ?>
                    <label for="password">password</label>
                    <input type="password" name="password" id="password" placeholder="enter your password">
                    <?= $validation->getErrorMessage("password") ?? "" ?>
                    <label for="password_confirmation">confirm your password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="enter your password again">
                    <?= $validation->getErrorMessage("password_confirmation") ?? "" ?>
                    <input type="submit" value="REGISTER">
                </form>
                <div><a href="login.php">Have an Account ?  Login Here</a></div>
            </div>
        </div>
    </div>
</body>
</html>