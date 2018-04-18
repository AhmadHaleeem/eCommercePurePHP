<?php

session_start();
if (isset($_SESSION['Username'])) {
  $pageTitle = 'Items';
  include "init.php";

  if (isset($_GET['do'])) {
  $do = $_GET['do'];
  } else {
  $do = 'Manage';
  }

  if ($do == 'Manage') { // Manage Page

    $stmt = $conn->prepare("SELECT items.*, categories.Name AS Category, users.Username AS Username FROM items
                            INNER JOIN categories ON categories.ID = items.Cat_ID
                            INNER JOIN users ON users.UserID = items.Member_ID  ORDER BY Item_ID DESC");
    $stmt->execute();
    $items = $stmt->fetchall();
    if (!empty($items)) {
    ?>
    <h1 class='text-center'>Manage Items</h1>
    <div class='container'>
      <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
          <tr>
            <td>#ID</td>
            <td>Name</td>
            <td>Description</td>
            <td>Price</td>
            <td>Add Date</td>
            <td>Category</td>
            <td>Username</td>
            <td>Control</td>
          </tr>
          <?php
            foreach($items as $item) {
              echo "<tr>";
                echo "<td>" .  $item['Item_ID'] . "</td>";
                echo "<td>" .  $item['Name'] . "</td>";
                echo "<td>" .  $item['Description'] . "</td>";
                echo "<td>" .  $item['Price'] . "</td>";
                echo "<td>" .  $item['Add_Date'] . "</td>";
                echo "<td>" .  $item['Category'] . "</td>";
                echo "<td>" .  $item['Username'] . "</td>";
                echo "<td>
                      <a href='items.php?do=Edit&Item_ID= " . $item['Item_ID'] . " ' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                      <a href='items.php?do=Delete&Item_ID= " . $item['Item_ID'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                      if ($item['Approve'] == 0) {
                        echo "<a href='items.php?do=Approve&Item_ID=" . $item['Item_ID'] . " ' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                      }
                echo "</td>";
              echo "</tr>";
            }

           ?>

        </table>
      </div>
      <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>
    </div>

    <?php } else {
      echo "<div class='container'>";
        echo "<div class='alert alert-danger'> There Are No Items To Manage</div>";
        echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>';
      echo "</div>";
    } ?>
<?php
  }elseif ($do == 'Add') { // Add Page
    ?>
    <h1 class='text-center'>Add New Item</h1>
    <div class='container'>
       <form class='form-horizontal' action="?do=Insert" method="POST">
          <!-- Start Name Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Name</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='name' class='form-control' required='required' placeholder="Name of The Item">
            </div>
          </div>
          <!-- End Name Field -->

          <!-- Start Description Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Description</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='description' class='form-control' required='required' placeholder="Descripe of The Item">
            </div>
          </div>
          <!-- End Description Field -->

          <!-- Start Price Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Price</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='price' class='form-control' required='required' placeholder="Price of The Item">
            </div>
          </div>
          <!-- End Price Field -->

          <!-- Start Country Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Country</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='country' class='form-control' required='required' placeholder="Country of Made">
            </div>
          </div>
          <!-- End Country Field -->

          <!-- Start Status Field -->
          <div class="form-group form-group-lg">
            <label class='col-sm-2 control-label'>Status</label>
            <div class="col-sm-10 col-md-6">
              <select name="status">
                <option value='0'>...</option>
                <option value='1'>New</option>
                <option value='2'>Like New</option>
                <option value='3'>Used</option>
                <option value='4'>Very Old</option>
              </select>
            </div>
          </div>
          <!-- End Status Field -->

          <!-- Start Members Field -->
          <div class="form-group form-group-lg">
            <label class='col-sm-2 control-label'>Member</label>
            <div class="col-sm-10 col-md-6">
              <select name="members">
                <option value='0'>...</option>
                <?php
                $getUsers = getAllFrom("*", "users", "", "", "UserID");
                foreach($getUsers as $user) {
                  echo "<option value= '" . $user['UserID'] ."'>" . $user['Username'] . "</option>";
                }
                 ?>
              </select>
            </div>
          </div>
          <!-- End Members Field -->

          <!-- Start Categories Field -->
          <div class="form-group form-group-lg">
            <label class='col-sm-2 control-label'>Category</label>
            <div class="col-sm-10 col-md-6">
              <select name="category">
                <option value='0'>...</option>
                <?php
                $getCats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID");
                foreach($getCats as $cat) {
                  echo "<option value= '" . $cat['ID'] ."'>" . $cat['Name'] . "</option>";
                  $getChilds = getAllFrom("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID");
                  foreach($getChilds as $childs) {
                    echo "<option value= '" . $childs['ID'] ."'>---" . $childs['Name'] . "</option>";
                  }
                }
                 ?>
              </select>
            </div>
          </div>
          <!-- End Categories Field -->
          <!-- Start Tags Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Tags</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='tags' class='form-control' placeholder="Write Any Tag">
            </div>
          </div>
          <!-- End Tags Field -->
          <!-- Start Add Field -->
          <div class='form-group form-group-lg'>
            <div class='col-sm-offset-2 col-sm-10'>
              <input type='submit' class='btn btn-primary btn-sm' value="Add Item">
            </div>
          </div>
          <!-- End Add Field -->
       </form>

    </div>
  <?php


  }elseif ($do == 'Insert') { // Insert Page
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      echo '<h1 class="text-center">Update Item</h1>';
      echo '<div class="container">';
      // Get The Variables From The Form

       $name    = $_POST['name'];
       $desc    = $_POST['description'];
       $price   = $_POST['price'];
       $country = $_POST['country'];
       $status  = $_POST['status'];
       $member  = $_POST['members'];
       $cat     = $_POST['category'];
       $tags    = $_POST['tags'];

       // Form Validation..
       $formErrors = array();
       if (empty($name)) {
         $formErrors[] = "Name Cant Be <strong>Empty</strong>..";
       }
       if (empty($desc)) {
         $formErrors[] = "Description Cant Be <strong>Empty</strong>..";
       }
       if (empty($price)) {
         $formErrors[] = "Price Cant Be <strong>Empty</strong>..";
       }
       if (empty($country)) {
         $formErrors[] = "Country Cant Be <strong>Empty</strong>..";
       }
       if ($status == 0) {
         $formErrors[] = "You Must Choose a <strong>Status</strong>..";
       }
       if ($member == 0) {
         $formErrors[] = "You Must Choose a <strong>Member</strong>..";
       }
       if ($cat == 0) {
         $formErrors[] = "You Must Choose a <strong>Category</strong>..";
       }

       foreach($formErrors as $errors) {
         echo '<div class="alert alert-danger">' . $errors . '</div>';

       }

       // Check If There is Not Any errors
       if (empty($formErrors)) {
           // Insert Userinfo For The Database
         $stmt = $conn->prepare("INSERT INTO `items`
                    (Name, Description, Price, Country_Made , Status, Add_Date, Cat_ID, Member_ID, Tags)
            VALUES  (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");
         $stmt->execute(array(
           'zname'    => $name,
           'zdesc'    => $desc,
           'zprice'   => $price,
           'zcountry' => $country,
           'zstatus'  => $status,
           'zmember'  => $member,
           'zcat'     => $cat,
           'ztags'    => $tags

         ));

         // Echo Success message
         $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted..' . "</div>";
         redirectHome($theMsg, 'Back');

     }
    } else {
      $theMsg =  "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly..</div>";
      redirectHome($theMsg);
    }
    echo "</div>";
  }

  elseif ($do == 'Edit') { // Edit Page
    // Check if get Request userid Is Numeric & Get The integet Value..
      $ItemID = isset($_GET['Item_ID']) && is_numeric($_GET['Item_ID']) ? intval($_GET['Item_ID']) : 0;

    // Select All Data Depend On This ID..
    $stmt = $conn->prepare('SELECT * FROM `items` WHERE Item_ID = ?');
    // Execute Query
    $stmt->execute(array($ItemID));
    // Fetch The Data
    $item = $stmt->fetch();
    $count = $stmt->rowCount();
    //Show The form if Id is Exist..
    if ($count > 0 ) {
      ?>
      <h1 class='text-center'>Edit Item</h1>
      <div class='container'>
         <form class='form-horizontal' action="?do=Update" method="POST">
           <input type="hidden" name="Item_ID" value="<?php echo $ItemID ?>">
            <!-- Start Name Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Name</label>
              <div class='col-sm-10 col-md-6'>
                <input type='text' name='name' class='form-control' required='required' placeholder="Name of The Item" value="<?php echo $item['Name']; ?>">
              </div>
            </div>
            <!-- End Name Field -->

            <!-- Start Description Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Description</label>
              <div class='col-sm-10 col-md-6'>
                <input type='text' name='description' class='form-control' required='required' placeholder="Descripe of The Item" value="<?php echo $item['Description']; ?>">
              </div>
            </div>
            <!-- End Description Field -->

            <!-- Start Price Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Price</label>
              <div class='col-sm-10 col-md-6'>
                <input type='text' name='price' class='form-control' required='required' placeholder="Price of The Item" value="<?php echo $item['Price']; ?>">
              </div>
            </div>
            <!-- End Price Field -->

            <!-- Start Country Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Country</label>
              <div class='col-sm-10 col-md-6'>
                <input type='text' name='country' class='form-control' required='required' placeholder="Country of Made" value="<?php echo $item['Country_Made']; ?>">
              </div>
            </div>
            <!-- End Country Field -->

            <!-- Start Status Field -->
            <div class="form-group form-group-lg">
              <label class='col-sm-2 control-label'>Status</label>
              <div class="col-sm-10 col-md-6">
                <select name="status">
                  <option value='1' <?php if ($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
                  <option value='2' <?php if ($item['Status'] == 2) { echo 'selected'; } ?>>Like New</option>
                  <option value='3' <?php if ($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
                  <option value='4' <?php if ($item['Status'] == 4) { echo 'selected'; } ?>>Very Old</option>
                </select>
              </div>
            </div>
            <!-- End Status Field -->

            <!-- Start Members Field -->
            <div class="form-group form-group-lg">
              <label class='col-sm-2 control-label'>Member</label>
              <div class="col-sm-10 col-md-6">
                <select name="members">

                  <?php
                  $stmt = $conn->prepare("SELECT * FROM `users`");
                  $stmt->execute();
                  $users = $stmt->fetchAll();
                  foreach($users as $user) {
                    echo "<option value= '" . $user['UserID'] ."'";
                     if ($item['Member_ID'] == $user['UserID']) {echo 'selected';}
                    echo ">" . $user['Username'] . "</option>";
                  }
                   ?>
                </select>
              </div>
            </div>
            <!-- End Members Field -->

            <!-- Start Categories Field -->
            <div class="form-group form-group-lg">
              <label class='col-sm-2 control-label'>Category</label>
              <div class="col-sm-10 col-md-6">
                <select name="category">

                  <?php
                  $stmt2 = $conn->prepare("SELECT * FROM `categories`");
                  $stmt2->execute();
                  $cats = $stmt2->fetchAll();
                  foreach($cats as $cat) {
                    echo "<option value= '" . $cat['ID'] ."'";
                      if ($item['Cat_ID'] == $cat['ID']) {echo 'selected';}
                    echo ">" . $cat['Name'] . "</option>";
                  }
                   ?>
                </select>
              </div>
            </div>
            <!-- End Categories Field -->
            <!-- Start Country Field -->
            <div class='form-group form-group-lg'>
              <label class="col-sm-2 control-label">Tags</label>
              <div class='col-sm-10 col-md-6'>
                <input type='text' name='tags' class='form-control' placeholder="Write Any Tag" value="<?php echo $item['Tags']; ?>">
              </div>
            </div>
            <!-- End Country Field -->
            <!-- Start Add Field -->
            <div class='form-group form-group-lg'>

              <div class='col-sm-offset-2 col-sm-10'>
                <input type='submit' class='btn btn-primary btn-sm' value="Save Item">
              </div>
            </div>
            <!-- End Add Field -->
         </form>
         <?php
         $stmt = $conn->prepare("SELECT comments.*, users.Username AS MemberName FROM `comments`
                                   INNER JOIN users ON users.UserID = comments.user_id
                                   WHERE item_id = ?");
         $stmt->execute(array($ItemID));
         $rows = $stmt->fetchall();
         if (!empty($rows)) {
         ?>
         <h1 class='text-center'>Manage [<?php echo $item['Name']; ?>] Comments</h1>
         <div class='container'>
           <div class="table-responsive">
             <table class="main-table text-center table table-bordered">
               <tr>

                 <td>Comment</td>
                 <td>User Name</td>
                 <td>Added Date</td>
                 <td>Control</td>
               </tr>
               <?php
                 foreach($rows as $row) {
                   echo "<tr>";
                     echo "<td>" .  $row['comment'] . "</td>";
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
           <?php } ?>
         </div>
      </div>
      <?php
    } else {
      $theMsg = "<div class='alert alert-danger'>Sorry There is No Such As This User ID..</div>";
      redirectHome($theMsg, 'Back');
    }
  }

  elseif ($do == 'Update') { // Update Page
    echo '<h1 class="text-center">Update Item</h1>';
    echo '<div class="container">';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get The Variables From The Form
       $id      = $_POST['Item_ID'];
       $name    = $_POST['name'];
       $desc    = $_POST['description'];
       $price   = $_POST['price'];
       $country = $_POST['country'];
       $status  = $_POST['status'];
       $cat     = $_POST['category'];
       $member  = $_POST['members'];
       $tags    = $_POST['tags'];

       // Form Validation..
       $formErrors = array();
       if (empty($name)) {
         $formErrors[] = "Name Cant Be <strong>Empty</strong>..";
       }
       if (empty($desc)) {
         $formErrors[] = "Description Cant Be <strong>Empty</strong>..";
       }
       if (empty($price)) {
         $formErrors[] = "Price Cant Be <strong>Empty</strong>..";
       }
       if (empty($country)) {
         $formErrors[] = "Country Cant Be <strong>Empty</strong>..";
       }
       if ($status == 0) {
         $formErrors[] = "You Must Choose a <strong>Status</strong>..";
       }
       if ($member == 0) {
         $formErrors[] = "You Must Choose a <strong>Member</strong>..";
       }
       if ($cat == 0) {
         $formErrors[] = "You Must Choose a <strong>Category</strong>..";
       }

       foreach($formErrors as $errors) {
         echo '<div class="alert alert-danger">' . $errors . '</div>';

       }

       // Check If There is Not Any errors
       if (empty($formErrors)) {
         // Update The Database With The Content
         $stmt = $conn->prepare("UPDATE `items` SET
                                                  Name = ?,
                                                  Description = ?,
                                                  Price = ?,
                                                  Country_Made = ?,
                                                  Status = ?,
                                                  Cat_ID = ?,
                                                  Member_ID = ?,
                                                  Tags = ?
                                                 WHERE
                                                  Item_ID = ? ");
         $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));
         $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated..' . "</div>";
         redirectHome($theMsg, 'Back');
       }

    } else {
      $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly..</div>";
      redirectHome($theMsg, 'Back');
    }
    echo "</div>";
  }

  elseif ($do == 'Delete') { // Delete Page
    echo '<h1 class="text-center">Delete Item</h1>';
    echo '<div class="container">';
    $ItemID = isset($_GET['Item_ID']) && is_numeric($_GET['Item_ID']) ? intval($_GET['Item_ID']) : 0;
    $check = checkItem('Item_ID', 'items', $ItemID); // This is Similar Down And This is The Second Way
    /*
    $stmt = $conn->prepare('SELECT * FROM `users` WHERE UserID = ?');

    $stmt->execute(array($userid));
    $count = $stmt->rowCount();
    */
    if ($check > 0) {
      $stmt = $conn->prepare('DELETE FROM `items` WHERE Item_ID = :zid');
      $stmt->bindParam(':zid', $ItemID);
      $stmt->execute();
      $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Row Deleted" ."</div>";
      redirectHome($theMsg, 'back');


    } else {
      $theMsg = "<div class='alert alert-danger'>There is No Such As This ID..</div>";
      redirectHome($theMsg);
      echo "</div>";
    }
  }

  elseif ($do == 'Approve') { // Activate Page
    echo '<h1 class="text-center">Approve Items</h1>';
    echo '<div class="container">';
    $ItemID = isset($_GET['Item_ID']) && is_numeric($_GET['Item_ID']) ? intval($_GET['Item_ID']) : 0;
    $check = checkItem('Item_ID', 'items', $ItemID);
    if ($check > 0) {
      $stmt = $conn->prepare("UPDATE `items` SET Approve = 1 WHERE Item_ID = ?");
      $stmt->execute(array($ItemID));
      $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Row Updated" ."</div>";
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
