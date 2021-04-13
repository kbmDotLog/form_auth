<?php
require_once "config.php";

if (array_key_exists("submit", $_POST)) {
    $email = $_POST['email'];

    //get the user's name
    $tran = "SELECT fname FROM users WHERE email = '$email' ";
    $output = $link->query($tran) or die("Error: " . mysqli_error($link));
    $tablerow = mysqli_fetch_array($output);
    $firstname = $tablerow['fname'];

    if (!empty($firstname)) {
        //generate the code
        $rand_txt = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $code = substr(str_shuffle($rand_txt), 0, 30);

        $code_ext = md5($code);
        $date = date("Y-m-d H:i:s");

        //insert the code in the forgot password table
        $tran = "INSERT INTO forgot (username, code, status, date_time) VALUES('$email', '$code_ext', 'Active', '$date')";
        $output = $link->query($tran) or die("Error: " . mysqli_error($link));

        //send email with activation link
        $subject = "Account Recovery";

        $message = "
                    <html>
                    <head>
                    <title>Account Recovery</title>
                    </head>
                    <body>
                    <h2>Hello $firstname,</h2>
                    <p>We received your request to change your password, please click on the button below to change your password. Please ignore if you didn't request for a change of password.</p>
                    <br>
                    <a href=\"http:localhost/form/recover_password.php?code=$code\" style=\"background-color: #39314f; color: #fff; font-size: 20px; padding: 10px 15px;border-radius: 10px;\">Change Password</a>
                    <br><br>
                    <p>Best Regards<br>form</p>
                    </body>
                    </html>
                    ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <no_reply@kosi.com>' . "\r\n";

        mail($email, $subject, $message, $headers);

        $success = "You successfully requested for a password reset. The reset link has been sent to your email, click on it to continue.";

    } else {
        $error = "The email provided is not registered with us.";
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="keywords" content="" />
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>

<body>
            <h1 class="mx-5 my-4">Forgot password</h1>
            <h4 class="mx-5 my-4">Type your e-mail to reset your password</h4>
            <form action="" method="post" class="mx-5 my-5">
                <div class="card">
                    <div class="card-body pb-1">
                    <?php
                                if (isset($success) && !empty($success)) {
                                ?>
                                    <div class="alert alert-success alert-dismissible">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Success!</strong> <?php echo $success; ?>
                                    </div>
                                <?php
                                }
                                ?>

                                <?php
                                if (isset($error) && !empty($error)) {
                                ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Error!</strong> <?php echo $error; ?>
                                    </div>
                                <?php
                                }
                                ?>
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="email1">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Your e-mail">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-button-group transparent">
                    <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">Reset Password</button>
                </div>

            </form>

</body>

</html>