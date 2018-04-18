<?php
session_start();
$pageTitle = 'Login';
if (isset($_SESSION['user'])) {
  header("location: index.php");
}

include "init.php";
// Check If The User Come From Http Request..
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['login'])) { // To Check If The Values Come From `Login Form`..
    $user = $_POST['Username'];
    $pass = $_POST['Password'];
    $hashedPass = sha1($pass);
    // Check if User Exsit In The Database..
    $stmt = $conn->prepare("SELECT UserID, Username, Password FROM `users` WHERE Username = ? AND Password = ? ");
    $stmt->execute(array($user, $hashedPass));
    $get = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) {
      $_SESSION['user'] = $user; // Register Session Name
      $_SESSION['uid']  = $get['UserID'];
      header("location: index.php");
      exit();
      }





    } else {




      $formErrors = array();
      $user  = $_POST['user'];
      $pass  = $_POST['pass'];
      $pass2 = $_POST['pass2'];
      $email = $_POST['email'];

      if (isset($user)) {
        $filterUser = filter_var($user, FILTER_SANITIZE_STRING);
        if (strlen($filterUser) < 4) {
          $formErrors[] = 'Sorry Your User Must Be At Least 4 Charchters';
        }
      } if (isset($pass) && isset($pass2)) {
          if (empty($pass)) {
            $formErrors[] = 'The Password Must Be Not Empty';
          }

          if (sha1($pass) !== sha1($pass2)) {
            $formErrors[] = 'Sorry The Password is Not Match';
          }
      }
      if (isset($email)) {
        $filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (filter_var($filterEmail, FILTER_VALIDATE_EMAIL) != true) {
          $formErrors[] = 'Sorry You Must Enter a Valid Email';
        }
      }
      if (empty($formErrors)) {
        $check = checkItem('Username', 'users', $user);
        if ($check == 1) {
          $formErrors[] = "Sorry This User is Exist";
        } else {
          $stmt = $conn->prepare("INSERT INTO users(Username, Password, Email, RegStatus, Datee)
                                            VALUES (:zuser, :zpass, :zemail, 0, Now())");
          $stmt->execute(array(
              "zuser"   => $user,
              'zpass'   => sha1($pass),
              'zemail'  => $email
          ));
          $succesMsg = 'Congratiolations You Are Now Registerd User';
        }
      }
    }
}

?>

<div class="container login-page">
  <h1 class="text-center">
    <span data-class="login" class="selected">Login</span> | <span  data-class="signup">Signup</span>
  </h1>
  <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="input-container">
      <input class="form-control" type="text" name="Username" placeholder="Enter Your Username" autocomplete="off" required='required'/>
    </div>
    <div class="input-container">
      <input class="form-control" type="password" name="Password" placeholder="Enter Your Password" autocomplete="new-password" required='required'/>
    </div>
    <div class="input-container">
      <input class="btn btn-primary btn-lg btn-block" name="login" type='submit' value="Login" />
    </div>
  </form>

  <form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="input-container">
      <input pattern=".{4,}" title="Username Must Be 4 Chars" class="form-control" type="text" name="user" placeholder="Type Your Username" autocomplete="off" required='required'/>
    </div>
    <div class="input-container">
      <input minlength='4' class="form-control" type="password" name="pass" placeholder="Type a Password" autocomplete="new-password" required='required'/>
    </div>
    <div class="input-container">
      <input class="form-control" type="password" name="pass2" placeholder="Type a Password Again" autocomplete="new-password" required='required'/>
    </div>
    <div class="input-container">
      <input class="form-control" type="email" name="email" placeholder="Type a Valid Email" required='required'/>
    </div>
    <input class="btn btn-success btn-lg btn-block" name="signup" type='submit' value="Signup" />
  </form>
  <div class="the-errors text-center">
    <?php
      if (!empty($formErrors)) {
        foreach($formErrors as $error) {
          echo "<div class='error'> $error </div>";
        }
      }
      if (isset($succesMsg)) {
        echo "<div class='msg success'>$succesMsg</div>";
      }
     ?>
  </div>
</div>



<?php include $tpl . "footer.php";?>
