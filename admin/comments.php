<?php
  session_start();
  if (isset($_SESSION['Username'])) {
    $pageTitle = 'Comments';
    include "init.php";

    if (isset($_GET['do'])) {
    $do = $_GET['do'];
    } else {
    $do = 'Manage';
    }

    if ($do == 'Manage') { // Mange Page

      $stmt = $conn->prepare("SELECT comments.*, items.Name AS ItemsName, users.Username AS MemberName FROM `comments`
                                INNER JOIN items ON items.Item_ID = comments.item_id
                                INNER JOIN users ON users.UserID = comments.user_id ORDER BY c_id DESC");
      $stmt->execute();
      $rows = $stmt->fetchAll();
      if (!empty($rows)) {
      ?>
      <h1 class='text-center'>Manage Comments</h1>
      <div class='container'>
        <div class="table-responsive">
          <table class="main-table text-center table table-bordered">
            <tr>
              <td>#ID</td>
              <td>Comment</td>
              <td>Item Name</td>
              <td>User Name</td>
              <td>Added Date</td>
              <td>Control</td>
            </tr>
            <?php
              foreach($rows as $row) {
                echo "<tr>";
                  echo "<td>" .  $row['c_id'] . "</td>";
                  echo "<td>" .  $row['comment'] . "</td>";
                  echo "<td>" .  $row['ItemsName'] . "</td>";
                  echo "<td>" .  $row['MemberName'] . "</td>";
                  echo "<td>" .  $row['comment_date'] . "</td>";
                  echo "<td>
                        <a href='comments.php?do=Edit&comid= " . $row['c_id'] . " ' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                        <a href='comments.php?do=Delete&comid= " . $row['c_id'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                        if ($row['status'] == 0) {
                          echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . " ' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                        }
                  echo "</td>";
                echo "</tr>";
              }
             ?>

          </table>
        </div>

      </div>
      <?php } else {
        echo "<div class='container'>";
          echo "<div class='alert alert-danger'> There Are No Comments To Manage</div>";
        echo "</div>";
      } ?>
<?php

    } elseif ($do == 'Edit') { // Edit Page

      // Check if get Request userid Is Numeric & Get The integet Value..
      $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

      // Select All Data Depend On This ID..
      $stmt = $conn->prepare('SELECT * FROM `comments` WHERE c_id = ?');
      // Execute Query
      $stmt->execute(array($comid));
      // Fetch The Data
      $row = $stmt->fetch();
      $count = $stmt->rowCount();
      //Show The form if Id is Exist..
      if ($count > 0 ) {

?>

    <h1 class='text-center'>Edit Comment</h1>
    <div class='container'>
       <form class='form-horizontal' action="?do=Update" method="POST">
          <!-- Start Comment Field -->
          <input type="hidden" name="comid" value="<?php echo $comid ?>">
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Comment</label>
            <div class='col-sm-10 col-md-6'>
              <textarea class='form-control' name='comment'><?php echo $row['comment']; ?></textarea>
            </div>
          </div>
          <!-- End Comment Field -->


          <!-- Start Save Field -->
          <div class='form-group form-group-lg'>
            <div class='col-sm-offset-2 col-sm-10'>
              <input type='submit' value='Save' class='btn btn-primary btn-lg' autocomplete='off'>
            </div>
          </div>
          <!-- End Save Field -->

       </form>
    </div>

 <?php
      } else {
        $theMsg = "<div class='alert alert-danger'>Sorry There is No Such As This User ID..</div>";
        redirectHome($theMsg, 'Back');
      }


    } elseif($do == 'Update') {
      echo '<h1 class="text-center">Update Comment</h1>';
      echo '<div class="container">';
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get The Variables From The Form
         $comid   = $_POST['comid'];
         $comment = $_POST['comment'];


           // Update The Database With The Content
           $stmt = $conn->prepare('UPDATE `comments` SET comment = ? WHERE c_id = ? ');
           $stmt->execute(array($comment, $comid));
           $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated..' . "</div>";
           redirectHome($theMsg);

      } else {
        $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly..</div>";
        redirectHome($theMsg, 'Back');
      }
      echo "</div>";



    } elseif ($do == 'Delete') {
      echo '<h1 class="text-center">Delete Comment</h1>';
      echo '<div class="container">';
      $comid = ($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
      $check = checkItem('c_id', 'comments', $comid); // This is Similar Down And This is The Second Way
      /*
      $stmt = $conn->prepare('SELECT * FROM `users` WHERE UserID = ?');

      $stmt->execute(array($userid));
      $count = $stmt->rowCount();
      */
      if ($check > 0) {
        $stmt = $conn->prepare('DELETE FROM `comments` WHERE c_id = :zid');
        $stmt->bindParam(':zid', $comid);
        $stmt->execute();
        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Row Deleted" ."</div>";
        redirectHome($theMsg, 'back');


      } else {
        $theMsg = "<div class='alert alert-danger'>There is No Such As This ID..</div>";
        redirectHome($theMsg);
        echo "</div>";
      }



    } elseif($do == 'Approve') {
      echo '<h1 class="text-center">Approve Comment</h1>';
      echo '<div class="container">';
      $comid = ($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
      $check = checkItem('c_id', 'comments', $comid);
      if ($check > 0) {
        $stmt = $conn->prepare("UPDATE `comments` SET status = 1 WHERE c_id = ?");
        $stmt->execute(array($comid));
        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Row Approved" ."</div>";
        redirectHome($theMsg, 'back');
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
