<?php 
session_start();

use App\Database\Models\User;
use App\Http\Request\Validation;

include "App/Http/Middlewares/guest.php";
include "App/Database/Models/User.php";
include "App/Http/Request/Validation.php";

$user = new User;
$validation = new Validation;

if($_SERVER['REQUEST_METHOD'] == "POST"){

    // make input validation
    $validation->setInputName("verification_code")->setInput($_POST['verification_code'])->Required()->Numeric()->Regex('/^[0-9]{5,5}$/' , "verification code must be 6 digits");
    
    // check if there is no errors 
    if(empty($validation->getErrors())){
        $user->setEmail($_SESSION['email'])->setVerification_code($_POST['verification_code']);
        // check if the verification code is correct 
        if($user->checkCode()){
            // check if the user came from forget-password page 
            if($_SESSION['page'] == "forget-password"){
                unset($_SESSION['page']);
                $message = "<div style='color:green; font-size:25px; margin-bottom:10px'>code is correct</div>";
                header('refresh:3;url=reset-password.php');
            }
            // check if the user came from register page
            elseif($_SESSION['page'] == "register"){
                $user->setEmail($_SESSION['email'])->setEmail_verified_at(date('Y-m-d H:i:s'));
            // verify the email
                if($user->verify()){
                    $userDetails = $user->getUserByEmail($_SESSION['email'])->fetch_assoc();
                    $_SESSION['userDetails'] = $userDetails;
                    unset($_SESSION['page']);
                    $message = "<div style='color:green; font-size:25px; margin-bottom:10px'>email verified correctly</div>";
                    header('refresh:3;url=index.php');
                }
            }
            // check if the user came from login page
            elseif($_SESSION['page'] == "login"){
                $user->setEmail($_SESSION['email'])->setEmail_verified_at(date('Y-m-d H:i:s'));
                // verify the email
                if($user->verify()){
                    $userDetails = $user->getUserByEmail($_SESSION['email'])->fetch_assoc();
                    $_SESSION['userDetails'] = $userDetails;
                    unset($_SESSION['page']);
                    $message = "<div style='color:green; font-size:25px; margin-bottom:10px'>email verified correctly</div>";
                    header('refresh:3;url=index.php');
                }
                
            }
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
                <h3>VERIFICATION CODE</h3>
                <form action="" method="POST">
                    <label for="verification_code">Enter Your Verification Code</label>
                    <input type="text" name="verification_code" id="verification_code" placeholder="enter your verification code">
                    <?= $validation->getErrorMessage("verification_code") ?? "" ?>
                    <input type="submit" value="SUBMIT">
                </form>
            </div>
        </div>
    </div>
</body>
</html>