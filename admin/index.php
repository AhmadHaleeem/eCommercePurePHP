<?php
session_start();
$navBar = ""; // Don't include navbar If This Variable Exist..
$pageTitle = 'Login';
if (isset($_SESSION['Username'])) {
  header("location: dashboardd.php");
}
 include "init.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $username = $_POST['username'];
  $password = $_POST['password'];
  $hashedPass = sha1($password);
  // Check if User Exsit In The Database..
  $stmt = $conn->prepare("SELECT
                                  UserID, Username, Password
                          FROM
                                 `users`
                          WHERE
                                  Username = ?
                          AND
                                  Password = ?
                          AND
                                  GroupID = 1
                          LIMIT 1 ");
  $stmt->execute(array($username, $hashedPass));
  $row = $stmt->fetch();
  $count = $stmt->rowCount();

  if ($count > 0) {
    $_SESSION['Username'] = $username; // Register Session Name
    $_SESSION['ID']       = $row['UserID'];   // Register Session ID
    header("location: dashboardd.php");
    exit();
    }
}
 ?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <h4 class="text-center">Admin Login</h4>
  <input class="form-control input-lg" type="text" name="username" placeholder="Username" autocomplete="off" />
  <input class="form-control input-lg"  type="password" name="password" placeholder="Password" autocomplete="new-password" />
  <input class="btn btn-primary btn-block btn-lg" type="submit" value="Login" />
</form>

<?php include $tpl . "footer.php"; ?>
