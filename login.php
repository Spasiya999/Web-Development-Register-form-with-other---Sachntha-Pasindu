<?php

session_start();

$name = $password = "";
$name_err = $password_err = "";

if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
  header('location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  require_once 'db-config.php';
  // name validation
  if (empty(trim($_POST['name']))) {
    $name_err = "Please Enter Your Name";
  } else {
    $name = trim($_POST['name']);
  }

  // password validation
  if (empty(trim($_POST['password']))) {
    $password_err = "Please Enter Password";
  } else {
    $password = trim($_POST['password']);
  }

  //check errors
  if (empty($name_err) && empty($password_err)) {
    $sql1 = "SELECT * FROM USERS WHERE username='" . $name . "'";

    $result = $con->query($sql1);

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();

      if ($user['password'] == sha1($password)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = array(
          'first_name' => $user['first_name'],
          'last_name' => $user['last_name'],
          'id' => $user['id']
        );

        header('location: index.php');
        exit;
      } else {
        $password_err = 'Invalid Password';
      }
      // continue with login logic
    } else {
      $name_err = 'Invalid Username';
    }
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="row m-3 justfy-center">
      <div class="col-4"></div>
      <div class="col-4 card">
        <h2 class="">Login</h2>
        <form method="post">
          <div class="mb-3">
            <label for="Name" class="form-label">Enter Your Username</label>
            <input type="text" class="form-control <?= empty($name_err) ?  '' : 'is-invalid' ?>" value="<?= $name_err ?>" name="name" placeholder="Username">
            <div class="invalid-feedback"><?= $name_err ?></div>
          </div>
          <div class="mb-3">
            <label for="Password" class="form-label">Enter Password</label>
            <input type="text" class="form-control <?= empty($password_err) ?  '' : 'is-invalid' ?>" name="password" id="password" placeholder="Password">
            <div class="invalid-feedback"><?= $password_err ?></div>
          </div>
          <div class="mb-3 text-center">
            <input type="submit" class="btn btn-primary w-100" name="submit" value="Login">
          </div>
      </div>
      <div class="col-4"></div>
      </form>
    </div>
  </div>
</body>

</html>