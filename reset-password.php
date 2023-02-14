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
    $validation->setInputName("password")->setInput($_POST['password'])->Required()->Regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/' , "Minimum eight and maximum 32 characters, at least one uppercase letter, one lowercase letter, one number and one special character");
    $validation->setInputName("password_confirmation")->setInput($_POST['password_confirmation'])->Required()->Confirmed($_POST['password']);
    
    // check if there is no errors 
    if(empty($validation->getErrors())){
        $user->setEmail($_SESSION['email'])->setPassword($_POST['password']);
        // update password
        if($user->UpdatePassword()){
            // redirect user to login page
            $message = "<div style='color:green; font-size:25px; margin-bottom:10px'>Password Reset Successfully</div>";
            unset($_SESSION['email']);
            header('refresh:3;url=login.php');
        }else{
            $message = "<div style='color:red; font-size:25px; margin-bottom:10px'>SomeThing Went Wrong</div>";
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
                    <label for="password">password</label>
                    <input type="password" name="password" id="password" placeholder="enter your password">
                    <?= $validation->getErrorMessage("password") ?? "" ?>
                    <label for="password_confirmation">confirm your password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="enter your password again">
                    <?= $validation->getErrorMessage("password_confirmation") ?? "" ?>
                    <input type="submit" value="SUBMIT">
                </form>
            </div>
        </div>
    </div>
</body>

</html>