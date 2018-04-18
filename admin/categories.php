<?php
session_start();
if (isset($_SESSION['Username'])) {
  $pageTitle = 'Category';
  include "init.php";

  if (isset($_GET['do'])) {
    $do = $_GET['do'];
  } else {
    $do = 'Manage';
  }

  if ($do == 'Manage') { // Manage Page
    $sort = 'ASC';
    $sort_array = array('ASC', 'DESC');
    if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
      $sort = $_GET['sort'];
    }
    $stmt2 = $conn->prepare("SELECT * FROM `categories` WHERE parent = 0 ORDER BY Ordering $sort");
    $stmt2->execute();
    $cats = $stmt2->fetchAll();
    if (!empty($cats)) {
     ?>
    <h1 class="text-center">Manage Categories</h1>
    <div class="container categories">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-edit"></i> Manage Categories
          <div class="option pull-right">
            <i class="fa fa-sort"></i> Ordering: [
            <a class="<?php if ($sort == 'ASC') {echo 'active';} ?>" href='?sort=ASC'>Asc</a> |
            <a  class="<?php if ($sort == 'DESC') {echo 'active';} ?>" href='?sort=DESC'>Desc</a> ]
            <i class="fa fa-eye"></i> View: [
            <span class="active" data-view='full'>Full</span> |
            <span data-view='classic'>Classic</span> ]
          </div>
        </div>
        <div class="panel-body">
          <?php
          foreach($cats as $cat) {
            echo "<div class='cat'>";
              echo "<div class='hidden-buttons'>";
                echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='btn btn-xs btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
              echo "</div>";
              echo "<h3>" . $cat['Name'] . "</h3>";
              echo "<div class='full-view'>";
              echo "<p>";
               if($cat['Description'] == '') {
                 echo "This Category Has No Description";
               }else {
                 echo $cat['Description'];
               }
              echo "</p>";
                $childCategory = getAllFrom("*", "categories", "WHERE Parent = {$cat['ID']}", " ", "ID", "ASC");
                if (!empty($childCategory)) {
                  echo "<h4 class='child-head'>Child Categories</h4>";
                  foreach($childCategory as $child) {
                    echo "<ul class='list-unstyled child-list'>";
                      echo "<li>
                            <a href='categories.php?do=Edit&catid=" . $child['ID'] . "' >" . $child['Name'] . "</a>
                            <a href='categories.php?do=Delete&catid=" . $child['ID'] . "' class='confirm child-delete'> Delete</a>
                            </li>";
                    echo "</ul>";
                  }
                }
                if ($cat['Visibility'] == 1) {echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';}
                if ($cat['Allow_Comment'] == 1) {echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>';}
                if ($cat['Allow_Ads'] == 1) {echo '<span class="ads"><i class="fa fa-close"></i> Ads Disabled</span>';}
              echo "</div>";
            echo "</div>";
            echo "<hr />";




          }
           ?>
        </div>
      </div>
      <a class="add-category btn btn-primary" href='categories.php?do=Add'><i class="fa fa-plus"></i> Add New Category</a>
    </div>
    <?php
  } else {
    echo "<div class='container'>";
      echo "<div class='alert alert-danger'> There Are No Categories To Manage</div>";
      echo "<a class='add-category btn btn-primary' href='categories.php?do=Add'><i class='fa fa-plus'></i> Add New Category</a>";
    echo "</div>"; }
     ?>
    <?php


  } elseif ($do == 'Add') { // Add Page
    ?>
    <h1 class='text-center'>Add New Category</h1>
    <div class='container'>
       <form class='form-horizontal' action="?do=Insert" method="POST">
          <!-- Start Name Field -->

          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Name</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='name' class='form-control' autocomplete="off" required='required' placeholder="Name of The Category">
            </div>
          </div>
          <!-- End Name Field -->

          <!-- Start Description Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Description</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='description' class='form-control' autocomplete="off" placeholder="Descripbe The Category">

            </div>
          </div>
          <!-- End Description Field -->

          <!-- Start Ordering Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Ordering</label>
            <div class='col-sm-10 col-md-6'>
              <input type='text' name='ordering' class='form-control' placeholder="Number To Arrange The Category">
            </div>
          </div>
          <!-- End Ordering Field -->

          <!-- Start Parent Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Parent ?</label>
            <div class='col-sm-10 col-md-6'>
              <select name="parent">
                <option value="0">None</option>
                <?php
                  $getParent = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
                  foreach($getParent as $parent) {
                    echo "<option value='" . $parent['ID'] ."'>" . $parent["Name"] . "</option>";
                  }

                 ?>
              </select>

            </div>
          </div>
          <!-- End Parent Field -->

          <!-- Start Visibility Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Visible</label>
            <div class='col-sm-10 col-md-6'>
              <div>
                <input id='vis-yes' type="radio" name="visible" value="0" checked />
                <label for='vis-yes'>Yes</label>
              </div>
              <div>
                <input id='vis-no' type="radio" name="visible" value="1" />
                <label for='vis-no'>No</label>
              </div>
            </div>
          </div>
          <!-- End Visibility Field -->
          <!-- Start Commenting Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Allow Commenting</label>
            <div class='col-sm-10 col-md-6'>
              <div>
                <input id='com-yes' type="radio" name="comment" value="0" checked />
                <label for='com-yes'>Yes</label>
              </div>
              <div>
                <input id='com-no' type="radio" name="comment" value="1" />
                <label for='com-no'>No</label>
              </div>
            </div>
          </div>
          <!-- End Commenting Field -->
          <!-- Start Ads Field -->
          <div class='form-group form-group-lg'>
            <label class="col-sm-2 control-label">Allow Ads</label>
            <div class='col-sm-10 col-md-6'>
              <div>
                <input id='ads-yes' type="radio" name="ads" value="0" checked />
                <label for='ads-yes'>Yes</label>
              </div>
              <div>
                <input id='ads-no' type="radio" name="ads" value="1" />
                <label for='ads-no'>No</label>
              </div>
            </div>
          </div>
          <!-- End Ads Field -->

          <!-- Start Add Field -->
          <div class='form-group form-group-lg'>

            <div class='col-sm-offset-2 col-sm-10'>
              <input type='submit' name='Add Category' class='btn btn-primary btn-lg' value="Add Category">
            </div>
          </div>
          <!-- End Add Field -->
       </form>

    </div>


  <?php
  }

  elseif ($do == 'Insert') { // Insert Category Page
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      echo '<h1 class="text-center">Insert Category</h1>';
      echo '<div class="container">';
      // Get The Variables From The Form

       $name    = $_POST['name'];
       $desc    = $_POST['description'];
       $order   = $_POST['ordering'];
       $parent  = $_POST['parent'];
       $visible = $_POST['visible'];
       $comment = $_POST['comment'];
       $ads     = $_POST['ads'];

         // Check If this User Exist In The datbase
         $checkUser = checkItem('Name', 'categories', $name);

         if ($checkUser == 1) {
           $theMsg = "<div class='alert alert-danger'>Sorry This Category Is Exist</div>";
           redirectHome($theMsg, 'Back');

         } else {

           // Insert Userinfo For The Database
         $stmt = $conn->prepare("INSERT INTO `categories` (Name, Description, Ordering, Parent, Visibility, Allow_Comment, Allow_Ads)
         VALUES (:zname, :zdesc, :zorder, :zparent, :zvisible, :zcomment, :zads)");
         $stmt->execute(array(
           'zname'     => $name,
           'zdesc'     => $desc,
           'zorder'    => $order,
           'zparent'   => $parent,
           'zvisible'  => $visible,
           'zcomment'  => $comment,
           'zads'      => $ads
         ));

         // Echo Success message
         $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted..' . "</div>";
         redirectHome($theMsg, 'Back');
       }

    } else {
      $theMsg =  "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly..</div>";
      redirectHome($theMsg, 'Back');
    }
    echo "</div>";
  }

  elseif ($do == 'Edit') { // Edit Page
    // Check if get Request userid Is Numeric & Get The integet Value..
    $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?
    intval($_GET['catid']) : 0;

    // Select All Data Depend On This ID..
    $stmt = $conn->prepare('SELECT * FROM `categories` WHERE ID = ?');
    // Execute Query
    $stmt->execute(array($catid));
    // Fetch The Data
    $cat = $stmt->fetch();
    $count = $stmt->rowCount();
    //Show The form if Id is Exist..
    if ($count > 0 ) {

?>

  <h1 class='text-center'>Edit Category</h1>
  <div class='container'>
    <form class='form-horizontal' action="?do=Update" method="POST">
      <input type="hidden" name="catid" value="<?php echo $catid ?>">
       <!-- Start Name Field -->

       <div class='form-group form-group-lg'>
         <label class="col-sm-2 control-label">Name</label>
         <div class='col-sm-10 col-md-6'>
           <input type='text' name='name' class='form-control' autocomplete="off" placeholder="Name of The Category" value="<?php echo $cat['Name']; ?>">
         </div>
       </div>
       <!-- End Name Field -->

       <!-- Start Description Field -->
       <div class='form-group form-group-lg'>
         <label class="col-sm-2 control-label">Description</label>
         <div class='col-sm-10 col-md-6'>
           <input type='text' name='description' class='form-control' autocomplete="off" placeholder="Descripbe The Category" value="<?php echo $cat['Description']; ?>">

         </div>
       </div>
       <!-- End Description Field -->

       <!-- Start Ordering Field -->
       <div class='form-group form-group-lg'>
         <label class="col-sm-2 control-label">Ordering</label>
         <div class='col-sm-10 col-md-6'>
           <input type='text' name='ordering' class='form-control' placeholder="Number To Arrange The Category" value="<?php echo $cat['Ordering']; ?>">
         </div>
       </div>
       <!-- End Ordering Field -->
       <!-- Start Parent Field -->
       <div class='form-group form-group-lg'>
         <label class="col-sm-2 control-label">Parent ?</label>
         <div class='col-sm-10 col-md-6'>
           <select name="parent">
             <option value="0">None</option>

             <?php
               $getParent = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
               foreach($getParent as $parent) {
                 echo "<option value='" . $parent['ID'] . "'";
                  if ($cat["Parent"] == $parent['ID']) {
                    echo " selected";
                  }
                 echo ">" . $parent["Name"] . "</option>";
               }

              ?>
           </select>

         </div>
       </div>
       <!-- End Parent Field -->
       <!-- Start Visibility Field -->
       <div class='form-group form-group-lg'>
         <label class="col-sm-2 control-label">Visible</label>
         <div class='col-sm-10 col-md-6'>
           <div>
             <input id='vis-yes' type="radio" name="visible" value="0" <?php if($cat['Visibility'] == 0) { echo 'checked'; } ?> />
             <label for='vis-yes'>Yes</label>
           </div>
           <div>
             <input id='vis-no' type="radio" name="visible" value="1"  <?php if($cat['Visibility'] == 1) { echo 'checked'; } ?> />
             <label for='vis-no'>No</label>
           </div>
         </div>
       </div>
       <!-- End Visibility Field -->
       <!-- Start Commenting Field -->
       <div class='form-group form-group-lg'>
         <label class="col-sm-2 control-label">Allow Commenting</label>
         <div class='col-sm-10 col-md-6'>
           <div>
             <input id='com-yes' type="radio" name="comment" value="0" <?php if($cat['Allow_Comment'] == 0) { echo 'checked'; } ?> />
             <label for='com-yes'>Yes</label>
           </div>
           <div>
             <input id='com-no' type="radio" name="comment" value="1" <?php if($cat['Allow_Comment'] == 1) { echo 'checked'; } ?> />
             <label for='com-no'>No</label>
           </div>
         </div>
       </div>
       <!-- End Commenting Field -->
       <!-- Start Ads Field -->
       <div class='form-group form-group-lg'>
         <label class="col-sm-2 control-label">Allow Ads</label>
         <div class='col-sm-10 col-md-6'>
           <div>
             <input id='ads-yes' type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0) { echo 'checked'; } ?> />
             <label for='ads-yes'>Yes</label>
           </div>
           <div>
             <input id='ads-no' type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1) { echo 'checked'; } ?> />
             <label for='ads-no'>No</label>
           </div>
         </div>
       </div>
       <!-- End Ads Field -->

       <!-- Start Add Field -->
       <div class='form-group form-group-lg'>

         <div class='col-sm-offset-2 col-sm-10'>
           <input type='submit' name='Add Category' class='btn btn-primary btn-lg' value="Save">
         </div>
       </div>
       <!-- End Add Field -->
    </form>
  </div>

<?php
    } else {
      $theMsg = "<div class='alert alert-danger'>Sorry There is No Such As This User ID..</div>";
      redirectHome($theMsg, 'Back');
    }
  }

  elseif ($do == 'Update') { // Update Page
    echo '<h1 class="text-center">Update Category</h1>';
    echo '<div class="container">';
    if ($_SERVER['REQUEST_METHOD'] == 'POST')   {
      // Get The Variables From The Form
       $id      = $_POST['catid'];
       $name    = $_POST['name'];
       $desc    = $_POST['description'];
       $order   = $_POST['ordering'];
       $parent  = $_POST['parent'];
       $visible = $_POST['visible'];
       $comment = $_POST['comment'];
       $ads     = $_POST['ads'];



         // Update The Database With The Content
         $stmt3 = $conn->prepare("UPDATE `categories` SET Name = ?, Description = ?, Ordering = ?, Parent = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ? ");
         $stmt3->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));

         $theMsg = "<div class='alert alert-success'>" . $stmt3->rowCount() . ' Record Updated..' . "</div>";
         redirectHome($theMsg, 'Back');


    } else {
      $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly..</div>";
      redirectHome($theMsg, 'Back');
    }
    echo "</div>";
  }

  elseif ($do == 'Delete') { // Delete Page
    echo '<h1 class="text-center">Delete Category</h1>';
    echo '<div class="container">';
    $catid = ($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
    $check = checkItem('ID', 'categories', $catid); // This is Similar Down And This is The Second Way
    /*
    $stmt = $conn->prepare('SELECT * FROM `users` WHERE UserID = ?');

    $stmt->execute(array($userid));
    $count = $stmt->rowCount();
    */
    if ($check > 0) {
      $stmt = $conn->prepare('DELETE FROM `categories` WHERE ID = :zuser');
      $stmt->bindParam(':zuser', $catid);
      $stmt->execute();
      $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Row Deleted" ."</div>";
      redirectHome($theMsg);


    } else {
      $theMsg = "<div class='alert alert-danger'>There is No Such As This ID..</div>";
      redirectHome($theMsg, 'back');
      echo "</div>";
    }
  }

  include $tpl . "footer.php";
} else {
  header("location: index.php");
  exit();
}
