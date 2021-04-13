<?php
// Include config file
require_once "config.php";


// Check if the user is already logged in, if yes then redirect him to home page
if (isset($_SESSION["id"])) {
    header("location: home.php");
    exit;
}

// Define variables and initialize with empty values
$fname = $lname = $email = $password = $confirm_password = $phone = $country = $username = "";
$fname_err = $lname_err = $email_err = $password_err = $confirm_password_err = $phone_err = $country_err = $username_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate First Name
    if (empty(trim($_POST["fname"]))) {
        $fname_err = "Please enter your First Name.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE fname = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_fname);

            // Set parameters
            $param_fname = trim($_POST["fname"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                $fname = trim($_POST["fname"]);
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate last name
    if (empty(trim($_POST["lname"]))) {
        $lname_err = "Please enter your Last Name.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE lname = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_lname);

            // Set parameters
            $param_lname = trim($_POST["lname"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                $lname = trim($_POST["lname"]);
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter a valid email.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate Phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your Phone Number.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE phone = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_phone);

            // Set parameters
            $param_phone = trim($_POST["phone"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                $phone = trim($_POST["phone"]);
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate Username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your Username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                $username = trim($_POST["username"]);
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Check input errors before inserting in database
    if (empty($fname_err) && empty($lname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($phone_err) && empty($username_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (fname, lname, email, password, phone, username) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_fname, $param_lname, $param_email, $param_password, $param_phone, $param_username);

            // Set parameters
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_phone = $phone;
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {

                // Redirect to dashboard page
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }



    // Close connection
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kosi</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>

<body>
    <h4 class="mx-5 my-4">Register</h4>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="my-5 mx-5">

        <div class="row">
            <div class="col-lg-6 form-group <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                <label class="text-small text-uppercase" for="firstName">First name</label>
                <input class="form-control form-control-lg" name="fname" id="firstName" type="text" placeholder="Enter your first name">
                <span class="help-block"><?php echo $fname_err; ?></span>
            </div>
            <div class="col-lg-6 form-group <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
                <label class="text-small text-uppercase" for="lastName">Last name</label>
                <input class="form-control form-control-lg" name="lname" id="lastName" type="text" placeholder="Enter your last name">
                <span class="help-block"><?php echo $lname_err; ?></span>
            </div>
            <div class="col-lg-6 form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label class="text-small text-uppercase" for="email">Email address</label>
                <input class="form-control form-control-lg" name="email" id="email" type="email" placeholder="e.g. Jason@example.com">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="col-lg-6 form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                <label class="text-small text-uppercase" for="phone">Phone number</label>
                <input class="form-control form-control-lg" name="phone" id="phone" type="tel" placeholder="e.g. +02 245354745">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>
            <div class="col-lg-6 form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label class="text-small text-uppercase" for="password">Password</label>
                <input class="form-control form-control-lg" name="password" id="password" type="password" placeholder="Your Password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="col-lg-6 form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label class="text-small text-uppercase" for="password">Confirm Password</label>
                <input class="form-control form-control-lg" name="confirm_password" id="confirm_password" type="password" placeholder="Your Password">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="col-lg-12 form-group" id="buyer">
                <div class="row">
                    <div class="col-lg-12 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label for="welcome">Username</label>
                        <input type="text" class="form-control border-none w-100" id="welcome" name="username" placeholder="Please Put your username">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 form-group">
                <button id="submit-btn" class="btn btn-dark" type="submit">Register</button>
            </div>
        </div>
    </form>