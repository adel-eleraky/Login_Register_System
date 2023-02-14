<?php 
session_start();

use App\Database\Models\User;
use App\Http\Request\Validation;
use App\Services\Media;

include "App/Http/Middlewares/auth.php";
include "App/Database/Models/User.php";
include "App/Http/Request/Validation.php";
include "App/Services/Media.php";

$user = new User;
$validation = new Validation;


if($_SERVER['REQUEST_METHOD'] == "POST"){

    // make input validation
    $validation->setInputName('name')->setInput($_POST['name'] ?? "")->Required()->String()->Between(2 , 32);
    $validation->setInputName('email')->setInput($_POST['email'] ?? "")->Required()->Regex('/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/');
    $validation->setInputName('phone')->setInput($_POST['phone'] ?? "")->Required()->Numeric()->Regex('/^01[0125][0-9]{8}$/' , "phone must be 11 number");

    // if the user change his email > check if it's unique 
    if($_POST['email'] != $_SESSION['userDetails']['email']){
        $validation->setInputName("email")->setInput($_POST['email'])->Unique("users" , "email");
    }
    // if the user change his phone > check if it's unique
    if($_POST['phone'] != $_SESSION['userDetails']['phone']){
        $validation->setInputName("phone")->setInput($_POST['phone'])->Unique("users" , "phone");
    }
    
    // if there is no validation error > make update in database 
    if(empty($validation->getErrors())){
        $user->setId($_SESSION['userDetails']['id'] ?? "")->setName($_POST['name'] ?? "")->setEmail($_POST['email'] ?? "")->setPhone($_POST['phone'] ?? "");
        if($user->updateDetails()){
            $message = "<div style='color:green; font-size:25px; margin-bottom:10px'>USER UPDATED SUCCESSFULLY</div>";
            $userDetails = $user->getUserByEmail($_POST['email'])->fetch_assoc();
            $_SESSION['userDetails'] = $userDetails;
        }
    }


    // check if the user want to update his photo
    if($_FILES['image']['error'] == 0){
        // make validation on photo
        $validation->setFile($_FILES['image'])->setInputName("image")->size(10**6)->Extensions(['png' , "jpg" , "jpeg"]);
        if(empty($validation->getErrors())){
            //  if there is no validation error > upload the photo to the server
            $media = new Media;
            if($media->setFile($_FILES['image'])->upload("assets/uploads/images/")){
                // delete the old photo
                if($_SESSION['userDetails']['image'] != "default.jpg"){
                    $media->Delete("assets/uploads/images/" . $_SESSION['userDetails']['image']);
                }
                // update the photo in database 
                $user->setImage($media->getNewMediaName())->setId($_SESSION['userDetails']['id']);
                if($user->uploadImage()){
                    $message .= "<br><div style='color:green; font-size:25px; margin-bottom:10px'>PROFILE PICTURE UPDATED SUCCESSFULLY</div>";
                    $userDetails = $user->getUserByEmail($_POST['email'])->fetch_assoc();
                    $_SESSION['userDetails'] = $userDetails;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style/master.css">
    <title>SYSTEM</title>
</head>
<body>
    <div class="header">
        <div class="container">
            <span><a href="index.php">LOGIN & REGISTER SYSTEM</a></span>
            <div class="nav-items">
                <ul>
                    <li><a href="logout.php">LOGOUT</a></li>
                    <li><a href="index.php">ACCOUNT</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="container">
            <div class="card">
                <?= $message ?? "" ?>
                <img src="assets/uploads/images/<?= $_SESSION['userDetails']['image'] ?>" id="image">
                <?= $validation->getErrorMessage("image") ?? "" ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <table class="table table-dark table-striped">
                        <tbody>
                            <tr>
                                <td><label for="file">Upload Photo</label></td>
                                <td>
                                    <input type="file" name="image" id="file" >
                                </td>
                            </tr>
                            <tr>
                                <td>NAME</td>
                                <td>
                                    <input type="text" name="name" value="<?= $_SESSION['userDetails']['name']  ?>">
                                    <?= $validation->getErrorMessage("name") ?? "" ?>
                                </td>
                            </tr>
                            <tr>
                                <td>EMAIL</td>
                                <td>
                                    <input type="email" name="email" value="<?= $_SESSION['userDetails']['email']  ?>">
                                    <?= $validation->getErrorMessage("email") ?? "" ?>
                                </td>
                            </tr>
                            <tr>
                                <td>PHONE</td>
                                <td>
                                    <input type="number" name="phone" value="<?= "0".$_SESSION['userDetails']['phone']  ?>">
                                    <?= $validation->getErrorMessage("phone") ?? "" ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" value="UPDATE">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<script>

</script>