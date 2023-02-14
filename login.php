<?php
session_start();
use App\Database\Models\User;
use App\Http\Request\Validation;
use App\Mail\verificationCode;

include "App/Http/Middlewares/guest.php";
include "App/Http/Request/Validation.php";
include "App/Database/Models/User.php";
include "App/Mail/verificationCode.php";


$user = new User;
$validation = new Validation;

if($_SERVER['REQUEST_METHOD'] == "POST"){

    // make input validation
    $validation->setInputName("email")->setInput($_POST['email'] ?? "")->Required()->Regex('/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/')->Exist($_POST['email']); // check if user's email exists in database
    $validation->setInputName("password")->setInput($_POST['password'] ?? "")->Required()->Regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/' , "Minimum eight and maximum 32 characters, at least one uppercase letter, one lowercase letter, one number and one special character");


    if(empty($validation->getErrors())){
        // after check the user's email , check if the password is correct
        if($user->CheckPassword($_POST['password'] , $_POST['email'])){
            $userDetails = $user->getUserByEmail($_POST['email'])->fetch_assoc();
            // check if the email is verified
            if(is_null($userDetails['email_verified_at'])){
                // send verification code to user's email
                $code = rand(10000 , 99999);  // verification code
                $user->setEmail($_POST['email'] ?? "")->setVerification_code($code)->UpdateCode();
                $verificationMail = new verificationCode($_POST['email'] , "verification code" , "Your Verification Code : {$code}");
                if($verificationMail->send()){
                    // redirect user to verification-code page
                    $message = "<div style='color:red; font-size:25px; margin-bottom:10px'>You Must Verify Your Email</div>";
                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['page'] = "login";
                    header('refresh:3;url=verification-code.php');
                }
            }else{
                // redirect to index page
                $_SESSION['userDetails'] = $userDetails;
                header("location:index.php");
            }
        }else{
            // display message if the email or password is wrong
            $message  = "<div style='color:red; font-size:25px; margin-bottom:10px'>Wrong Email or Password</div>";
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
                <form action="" method="POST">
                    <label for="email">email</label>
                    <input type="email" name="email" id="email" placeholder="enter your email">
                    <?= $validation->getErrorMessage("email"); ?>
                    <label for="password">password</label>
                    <input type="password" name="password" id="password" placeholder="enter your password">
                    <?= $validation->getErrorMessage("password"); ?>
                    <input type="submit" value="LOGIN">
                </form><br>
                <div><a href="forget-password.php">Forget Your Password ?</a></div><br>
                <div><a href="register.php">Don't Have an Account ?  Sign Up Here</a></div>
            </div>
        </div>
    </div>
</body>
</html>