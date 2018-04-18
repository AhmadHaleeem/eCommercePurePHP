<?php
  session_start();
  if (isset($_SESSION['Username'])) {
    $pageTitle = 'Members';
    include "init.php";

    if (isset($_GET['do'])) {
    $do = $_GET['do'];
    } else {
    $do = 'Manage';
    }

    if ($do == 'Manage') { // Mange Page
      $query = '';
      if (isset($_GET['page']) && $_GET['page'] == 'pending') {
        $query = 'AND RegStatus = 0';
      }
      $stmt = $conn->prepare("SELECT * FROM `users` WHERE GroupID != 1 $query  ORDER BY UserID DESC");
      $stmt->execute();
      $rows = $stmt->fetchall();
      if (!empty($rows)) {
      ?>
      <h1 class='text-center'>Manage Member</h1>
      <div class='container'>
        <div class="table-responsive">
          <table class="main-table text-center table table-bordered">
            <tr>
              <td>#ID</td>
              <td>Avatar</td>
              <td>Username</td>
              <td>Email</td>
              <td>Full Name</td>
              <td>Registerd Date</td>
              <td>Control</td>
            </tr>
            <?php
              foreach($rows as $row) {
                echo "<tr>";
                  echo "<td>" .  $row['UserID'] . "</td>";
                  echo "<td>";
                    echo "<img src='uploads/avatar/" . $row['Avatar'] . "' alt='' />";
                  echo "</td>";
                  echo "<td>" .  $row['Username'] . "</td>";
                  echo "<td>" .  $row['Email'] . "</td>";
                  echo "<td>" .  $row['FullName'] . "</td>";
                  echo "<td>" .  $row['Datee'] . "</td>";
                  echo "<td>
                        <a href='members.php?do=Edit&UserID= " . $row['UserID'] . " ' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                        <a href='members.php?do=Delete&UserID= " . $row['UserID'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                        if ($row['RegStatus'] == 0) {
                          echo "<a href='members.php?do=Activate&UserID=" . $row['UserID'] . " ' class='btn btn-info activate'><i class='fa fa-check'></i>Activate</a>";
                        }
                  echo "</td>";
                echo "</tr>";
              }
             ?>

          </table>
        </div>
        <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
      </div>
      <?php } else {
        echo "<div class='container'>";
          echo "<div class='alert alert-danger'> There Are No Users To Manage</div>";
          echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
        echo "</div>";

      } ?>
<?php
    } elseif($do == 'Add') { // Add member Page ?>
      <h1 class='text-center'>Add New Member</h1>
      <div class='container'>
         <form class='form-horizontal' action="?do=Insert" method="POST" enctype="multipart/form-data">
            <!-- Start Username Field -->
            <input type="hidden" name="userid" value="<?php echo $userid ?>">
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Username</label>
              <div class='col-sm-10 col-md-6'>
                <input type='text' name='username' class='form-control' autocomplete='off' required='required' placeholder="Write Your Username">
              </div>
            </div>
            <!-- End Username Field -->

            <!-- Start Password Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Password</label>
              <div class='col-sm-10 col-md-6'>
                <input type='Password' name='Password' class='password form-control' autocomplete='no-password' placeholder="The Password Must Be Strong" required='required'>
                <i class="show-pass fa fa-eye fa-2x"></i>
              </div>
            </div>
            <!-- End Password Field -->

            <!-- Start Email Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Email</label>
              <div class='col-sm-10 col-md-6'>
                <input type='Email' name='Email' class='form-control' autocomplete='off' required='required' placeholder="Write a Valid Email">
              </div>
            </div>
            <!-- End Email Field -->

            <!-- Start Fullname Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Fullname</label>
              <div class='col-sm-10 col-md-6'>
                <input type='text' name='Fullname' class='form-control' autocomplete='off' required='required' placeholder="Please Write Your Fullname">
              </div>
            </div>
            <!-- End Fullname Field -->

            <!-- Start Avatar Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">User Avatar</label>
              <div class='col-sm-10 col-md-6'>
                <input type='file' name='avatar' class='form-control' required='required' >
              </div>
            </div>
            <!-- End Avatar Field -->

            <!-- Start Fullname Field -->
            <div class='form-group form-group-lg'>
              <div class='col-sm-offset-2 col-sm-10'>
                <input type='submit' name='Add New Member' class='btn btn-primary btn-lg'>
              </div>
            </div>
            <!-- End Fullname Field -->
         </form>
      </div>



  <?php
} elseif($do == 'Insert') { // Insert Member Page


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo '<h1 class="text-center">Insert Member</h1>';
    echo '<div class="container">';
    // Get The Variables From The Form

     $avatarName = $_FILES['avatar']['name'];
     $avatarSize = $_FILES['avatar']['size'];
     $avatarTmpN = $_FILES['avatar']['tmp_name'];
     $avatarType = $_FILES['avatar']['type'];

    // Avatar Extentions
    $avatarAllowedExtentions = array("jpeg", "jpg", "png", "gif");
    $avatarExtenstion = strtolower(end(explode(".", $avatarName)));

     $user  = $_POST['username'];
     $email = $_POST['Email'];
     $fulln = $_POST['Fullname'];
     $pass  = $_POST['Password'];
     $hashpass = sha1($_POST['Password']);

     // Form Validation..
     $formErrors = array();
     if (strlen($user) < 4) {
       $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>..';
     }
     if (strlen($user) > 20) {
       $formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>..';
     }
     if (empty($user)) {
       $formErrors[] = 'Username Cant Be <strong>Empty</strong>..';
     }
     if (empty($pass)) {
       $formErrors[] = 'Password Cant Be <strong>Empty</strong>..';
     }
     if (empty($email)) {
       $formErrors[] = 'Email Cant Be <strong>Empty</strong>..';
     }
     if (empty($fulln)) {
       $formErrors[] = 'Fullname Cant Be <strong>Empty</strong>..';
     }
     if (!empty($avatarName) && !in_array($avatarExtenstion, $avatarAllowedExtentions)) {
       $formErrors[] = 'This Extenstion is Not <strong>Allowed</strong>..';
     }
     if (empty($avatarName)) {
       $formErrors[] = 'Avatar Photo Cant Be <strong>Empty</strong>..';
     }
     if ($avatarSize > 4194304) {
       $formErrors[] = 'Avatar Photo Cant Be More Than <strong>4MB</strong>..';
     }
     foreach($formErrors as $errors) {
       echo '<div class="alert alert-danger">' . $errors . '</div>';
     }

     // Check If There is Not Any errors
     if (empty($formErrors)) {
       $avatar = rand(0, 100000000) . "_" . $avatarName;
       move_uploaded_file($avatarTmpN, 'uploads\avatar\\' . $avatar);
       // Check If this User Exist In The datbase
       $checkUser = checkItem('Username', 'users', $user);
       $checkEmail = checkItem('Email', 'users', $email);
       if ($checkUser || $checkEmail == 1) {
         $theMsg = "<div class='alert alert-danger'>Sorry This User or This Email Is Exist</div>";
         redirectHome($theMsg, 'Back');

       } else {

         // Insert Userinfo For The Database
       $stmt = $conn->prepare("INSERT INTO `users` (Username, Password, Email, FullName, RegStatus, Datee, Avatar) VALUES (:zuser, :zpass, :zemail, :zfull, 1, now(), :zavatar)");
       $stmt->execute(array(
         'zuser'   => $user,
         'zpass'   => $hashpass,
         'zemail'  => $email,
         'zfull'   => $fulln,
         'zavatar' => $avatar
       ));

       // Echo Success message
       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted..' . "</div>";
       redirectHome($theMsg, 'Back');
     }

   }

  } else {
    $theMsg =  "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly..</div>";
    redirectHome($theMsg, 'Back');
  }

  echo "</div>";

    } elseif ($do == 'Edit') { // Edit Page

      // Check if get Request userid Is Numeric & Get The integet Value..
      $userid = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;

      // Select All Data Depend On This ID..
      $stmt = $conn->prepare('SELECT * FROM `users` WHERE UserID = ? LIMIT 1');
      // Execute Query
      $stmt->execute(array($userid));
      // Fetch The Data
      $row = $stmt->fetch();
      $count = $stmt->rowCount();
      //Show The form if Id is Exist..
      if ($count > 0 ) {

?>

    <h1 class='text-center'>Edit Member</h1>
    <div class='container'>
       <form class='form-horizontal' action="?do=Update" method="POST">
          <!-- Start Username Field -->
          <input type="hidden" name="userid" value="<?php echo $userid ?>">
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Username</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='username' value='<?php echo $row['Username']; ?>' class='form-control' autocomplete='off' required='required'>
            </div>
          </div>
          <!-- End Username Field -->

          <!-- Start Password Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Password</label>
            <div class='col-sm-10 col-md-6'>
              <input type='hidden' name='oldPassword' value='<?php echo $row['Password']; ?>'>
              <input type='Password' name='newPassword' class='form-control' autocomplete='no-password' placeholder="Leave Blank If You Dont Want Change">
            </div>
          </div>
          <!-- End Password Field -->

          <!-- Start Email Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Email</label>
            <div class='col-sm-10 col-md-6'>
              <input type='Email' name='Email' value='<?php echo $row['Email']; ?>'  class='form-control' autocomplete='off' required='required'>
            </div>
          </div>
          <!-- End Email Field -->

          <!-- Start Fullname Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Fullname</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='Fullname' value='<?php echo $row['FullName']; ?>'  class='form-control' autocomplete='off' required='required'>
            </div>
          </div>
          <!-- End Fullname Field -->

          <!-- Start Avatar Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">User Avatar</label>
            <div class='col-sm-10 col-md-6'>
              <input type='file' name='avatar' value="<?php echo $row['Avatar'];  ?>" class='form-control' required='required' >
            </div>
          </div>
          <!-- End Avatar Field -->

          <!-- Start Fullname Field -->
          <div class='form-group form-group-lg'>

            <div class='col-sm-offset-2 col-sm-10'>
              <input type='submit' name='Save' class='btn btn-primary btn-lg' autocomplete='off'>
            </div>
          </div>
          <!-- End Fullname Field -->

       </form>
    </div>

 <?php
      } else {
        $theMsg = "<div class='alert alert-danger'>Sorry There is No Such As This User ID..</div>";
        redirectHome($theMsg, 'Back');
      }
    } elseif($do == 'Update') {
      echo '<h1 class="text-center">Update Member</h1>';
      echo '<div class="container">';
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get The Variables From The Form
         $id    = $_POST['userid'];
         $user  = $_POST['username'];
         $email = $_POST['Email'];
         $fulln = $_POST['Fullname'];

         // Check If The Password Is Empty..
         $pass = (empty($_POST['newPassword'])) ? $_POST['oldPassword'] : sha1($_POST['newPassword']);
         // Form Validation..
         $formErrors = array();
         if (strlen($user) < 4) {
           $formErrors[] = '<div class="alert alert-danger">Username Cant Be Less Than <strong>4 Characters</strong>..</div>';
         }
         if (strlen($user) > 20) {
           $formErrors[] = '<div class="alert alert-danger">Username Cant Be More Than <strong>20 Characters</strong>..</div>';
         }
         if (empty($user)) {
           $formErrors[] = '<div class="alert alert-danger">Username Cant Be <strong>Empty</strong>..</div>';
         }
         if (empty($email)) {
           $formErrors[] = '<div class="alert alert-danger">Email Cant Be <strong>Empty</strong>..</div>';
         }
         if (empty($fulln)) {
           $formErrors[] = '<div class="alert alert-danger">Fullname Cant Be <strong>Empty</strong>..</div>';
         }
         foreach($formErrors as $errors) {
           echo $errors;
         }

         // Check If There is Not Any errors
         if (empty($formErrors)) {

           $stmt2 = $conn->prepare("SELECT * FROM `users` WHERE Username = ? AND UserID != ?");
           $stmt2->execute(array($user, $id));
           $count = $stmt2->rowCount();
           if ($count == 1) {
             $theMsg = "<div class='alert alert-danger'> Sorry This User is Exist..</div>";
             redirectHome($theMsg, 'Back');
           } else {

             // Update The Database With The Content
             $stmt = $conn->prepare('UPDATE `users` SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ? ');
             $stmt->execute(array($user, $email, $fulln, $pass, $id));
             $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated..' . "</div>";
             redirectHome($theMsg, 'Back');
           }

         }

      } else {
        $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly..</div>";
        redirectHome($theMsg, 'Back');
      }
      echo "</div>";



    } elseif ($do == 'Delete') {
      echo '<h1 class="text-center">Delete Member</h1>';
      echo '<div class="container">';
      $userid = ($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
      $check = checkItem('UserID', 'users', $userid);
       // This is Similar Down And This is The Second Way
      /*
      $stmt = $conn->prepare('SELECT * FROM `users` WHERE UserID = ?');

      $stmt->execute(array($userid));
      $count = $stmt->rowCount();
      */
      if ($check > 0) {
        $stmt = $conn->prepare('DELETE FROM `users` WHERE UserID = :zuser');
        $stmt->bindParam(':zuser', $userid);
        $stmt->execute();
        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Row Deleted" ."</div>";
        redirectHome($theMsg, 'back');


      } else {
        $theMsg = "<div class='alert alert-danger'>There is No Such As This ID..</div>";
        redirectHome($theMsg);
        echo "</div>";
      }



    } elseif($do == 'Activate') {
      echo '<h1 class="text-center">Activate Member</h1>';
      echo '<div class="container">';
      $userid = ($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
      $check = checkItem('UserID', 'users', $userid);
      if ($check > 0) {
        $stmt = $conn->prepare("UPDATE `users` SET RegStatus = 1 WHERE UserID = ?");
        $stmt->execute(array($userid));
        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Row Updated" ."</div>";
        redirectHome($theMsg);
      } else {
        $theMsg = "<div class='alert alert-danger'>There is No Such As This ID..</div>";
        redirectHome($theMsg);
        echo "</div>";
      }


    }
    include $tpl . "footer.php";
  } else {
    header("location: index.php");
    exit();
  }
