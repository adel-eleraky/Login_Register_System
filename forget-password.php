<?php 
session_start();

use App\Database\Models\User;
use App\Http\Request\Validation;
use App\Mail\verificationCode;

include "App/Http/Middlewares/guest.php";
include "App/Database/Models/User.php";
include "App/Http/Request/Validation.php";
include "App/Mail/verificationCode.php";

$user = new User;
$validation = new Validation;


if($_SERVER['REQUEST_METHOD'] == "POST"){

    // make input validation
    $validation->setInputName("email")->setInput($_POST['email'])->Required()->Regex('/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/');
    
    // check if there is no errors 
    if(empty($validation->getErrors())){
        // check if user's email exist in database 
        $userDetails =  $user->getUserByEmail($_POST['email']);
        if($userDetails->num_rows == 1){
            // update verification code in database 
            $code = rand(10000 , 99999);
            $user->setEmail($_POST['email'] ?? "")->setVerification_code($code);
            $user->UpdateCode();
            // send verification code to user's email
            $verificationMail = new verificationCode($_POST['email'] , "reset password" , "Your Verification Code : $code");
            if($verificationMail->send()){
                // redirect user to to verification_code page 
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['page'] = "forget-password";
                header('location:verification-code.php');
            }else{
                $message = "<div style='color:red; font-size:25px; margin-bottom:10px'>SomeThing Went Wrong</div>";
            }
        }else{
            $message = "<div style='color:red; font-size:25px; margin-bottom:10px'>Email Doesn't Exist In Database</div>";
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
                <h3>RESET PASSWORD</h3>
                <form action="" method="POST">
                    <label for="email">Enter Your Email</label>
                    <input type="email" name="email" id="email" placeholder="enter your email">
                    <?= $validation->getErrorMessage("email") ?? "" ?>
                    <input type="submit" value="SUBMIT">
                </form>
            </div>
        </div>
    </div>
</body>
</html>