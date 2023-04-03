<?php
$first_name = $last_name = $username = $password = $confirm_password = '';
$first_name_err = $last_name_err = $username_err = $password_err = $confirm_password_err = '';

$validation_failed = false;

if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header('location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once 'db-config.php';
    //first name validation
    if (empty(trim($_POST['first_name']))) {
        $first_name_err = "Please enter first name";
        $validation_failed = true;
    } else {
        $first_name = trim($_POST['first_name']);
    }
    //last name validation
    if (empty(trim($_POST['last_name']))) {
        $last_name_err = "Please enter last name";
        $validation_failed = true;
    } else {
        $last_name = trim($_POST['last_name']);
    }
    // username validation
    if (empty(trim($_POST['username']))) {
        $username_err = "Please Enter Username";
        $validation_failed = true;
    } else {
        $username = trim($_POST['username']);
    }

    // password validation
    if (empty(trim($_POST['password']))) {
        $password_err = "Please Enter Password";
        $validation_failed = true;
    } else {
        $password = trim($_POST['password']);
    }

    // confirm password validation
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = "Please Confirm Password";
        $validation_failed = true;
    } else {
        $confirm_password = trim($_POST['confirm_password']);
    }

    //check if password and confirm password are the same
    if ($password != $confirm_password) {
        $validation_failed = true;
        $confirm_password_err = "Passwords do not match";
    }

    if (!$validation_failed) {

        $stm = $con->prepare("INSERT INTO users (first_name, last_name, username, password, reg_date, status) VALUES (?, ?, ?, ?, ?, ?)");

        $status = 'active';
        $reg_date = time();

        $hashed_password = sha1($password);
        $stm->bind_param('ssssis',  $first_name, $last_name, $username, $hashed_password, $reg_date, $status);

        $result = $stm->execute();

        if ($result) {
            header('location: login.php');
        } else {
            $error = 'Somthing wen wrong';
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row m-3 justfy-center">
            <div class="col-4"></div>
            <div class="col-4 card">
                <h2 class="">Login</h2>
                <form method="post">

                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="First Name" class="form-label">Enter Your First Name</label>
                        <input type="text" class="form-control <?= empty($first_name_err) ?  '' : 'is-invalid' ?>" value="<?= $first_name_err ?>" name="first_name" placeholder="First Name">
                        <div class="invalid-feedback"><?= $first_name_err ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="Last Name" class="form-label">Enter Your Last Name</label>
                        <input type="text" class="form-control <?= empty($last_name_err) ?  '' : 'is-invalid' ?>" value="<?= $last_name_err ?>" name="last_name" placeholder="Last Name">
                        <div class="invalid-feedback"><?= $last_name_err ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="Name" class="form-label">Enter Your Username</label>
                        <input type="text" class="form-control <?= empty($username_err) ?  '' : 'is-invalid' ?>" value="<?= $username_err ?>" name="username" placeholder="Username">
                        <div class="invalid-feedback"><?= $username_err ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="Password" class="form-label">Enter Password</label>
                        <input type="text" class="form-control <?= empty($password_err) ?  '' : 'is-invalid' ?>" name="password" id="password" placeholder="Password">
                        <div class="invalid-feedback"><?= $password_err ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_confirm_password" class="form-label">Confirm Password</label>
                        <input type="text" class="form-control <?= empty($confirm_password_err) ?  '' : 'is-invalid' ?>" name="confirm_password" id="confirm_password" placeholder="confirm_password">
                        <div class="invalid-feedback"><?= $confirm_password_err ?></div>
                    </div>
                    <div class="mb-3 text-center">
                        <input type="submit" class="btn btn-primary w-100 " name="Register" value="Register">
                    </div>
            </div>
            <div class="col-4"></div>
            </form>
        </div>
    </div>
</body>

</html>