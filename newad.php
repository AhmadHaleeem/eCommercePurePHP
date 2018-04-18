<?php
session_start();
$pageTitle = 'Create New Item';
include "init.php";
if (isset($_SESSION['user'])) {

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formErrors = array();
    $name     = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $desc     = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $country  = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
    $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $tags     = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
      if (strlen($name) < 4) {
        $formErrors[] = 'Item Name Must Be At Least 4 Characters';
      }
      if (strlen($desc) < 10) {
        $formErrors[] = 'Item Description Must Be At Least 4 Characters';
      }
      if (empty($price)) {
        $formErrors[] = 'Item Price Must Be Must Be Not Empty';
      }
      if (strlen($country) < 2) {
        $formErrors[] = 'Item Country Must Be At Least 2 Characters';
      }
      if (empty($status)) {
        $formErrors[] = 'Item Status Must Be Must Be Not Empty';
      }
      if (empty($category)) {
        $formErrors[] = 'Item Category Must Be Not Empty';
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
          'zcat'     => $category,
          'zmember'  => $_SESSION['uid'],
          'ztags'    => $tags

        ));

        // Echo Success message
        if ($stmt) {
          $succesMsg = 'New Item Has Been Added..';
        }

    }
  }


?>
<h1 class="text-center"><?php echo $pageTitle; ?></h1>
  <div class='create-ad block'>
    <div class="container">
      <div class="panel panel-primary">
        <div class="panel-heading"><?php echo $pageTitle; ?></div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-8">

                 <form class='form-horizontal main-form' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <!-- Start Name Field -->
                    <div class='form-group form-group-lg'>
                      <label class="col-sm-3 control-label">Name</label>
                      <div class='col-sm-9 col-md-9'>
                        <input type='text' name='name' class='form-control live' data-class='.live-title' required='required'  placeholder="Name of The Item">
                      </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class='form-group form-group-lg'>
                      <label class="col-sm-3 control-label">Description</label>
                      <div class='col-sm-10 col-md-9'>
                        <input type='text' name='description' class='form-control live' data-class='.live-desc' required='required' placeholder="Descripe of The Item">
                      </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Price Field -->
                    <div class='form-group form-group-lg'>
                      <label class="col-sm-3 control-label">Price</label>
                      <div class='col-sm-10 col-md-9'>
                        <input type='text' name='price' class='form-control live' data-class='.live-price'  required='required' placeholder="Price of The Item">
                      </div>
                    </div>
                    <!-- End Price Field -->

                    <!-- Start Country Field -->
                    <div class='form-group form-group-lg'>
                      <label class="col-sm-3 control-label">Country</label>
                      <div class='col-sm-10 col-md-9'>
                        <input type='text' name='country' class='form-control' required='required' placeholder="Country of Made">
                      </div>
                    </div>
                    <!-- End Country Field -->

                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                      <label class='col-sm-3 control-label'>Status</label>
                      <div class="col-sm-10 col-md-9">
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



                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                      <label class='col-sm-3 control-label'>Category</label>
                      <div class="col-sm-10 col-md-9">
                        <select name="category">
                          <option value='0'>...</option>
                          <?php

                          $cats = getAllFrom("*", "categories", "WHERE Parent = 0 ", " ", 'ID');
                          foreach($cats as $cat) {
                            echo "<option value= '" . $cat['ID'] ."'>" . $cat['Name'] . "</option>";
                            $subCats = getAllFrom("*", "categories", "WHERE Parent = {$cat['ID']} ", " ", 'ID');
                            foreach($subCats as $child) {
                              echo "<option value='" . $child['ID'] . "'>---" . $child['Name'] . "</option>";
                            }
                          }
                           ?>
                        </select>
                      </div>
                    </div>
                    <!-- End Categories Field -->

                    <!-- Start Tags Field -->
                    <div class='form-group form-group-lg'>
                      <label class="col-sm-3 control-label">Tags</label>
                      <div class='col-sm-10 col-md-9'>
                        <input type='text' name='tags' class='form-control' placeholder="Write Any Tag">
                      </div>
                    </div>
                    <!-- End Tags Field -->

                    <!-- Start Add Field -->
                    <div class='form-group form-group-lg'>
                      <div class='col-sm-offset-3 col-sm-9'>
                        <input type='submit' class='btn btn-primary btn-sm' value="Add Item">
                      </div>
                    </div>
                    <!-- End Add Field -->
                 </form>

            </div>
            <div class="col-md-4">
              <div class='thumbnail item-box live-preview'>
                <span class='price-tag'>$
                  <span class="live-price">0</span>
                </span>
                <img class='img-responsive' src='test.jpg' alt='' />
                <div class='caption'>
                   <h3 class="live-title">Title</h3>
                   <p class="live-desc">Description</p>
                </div>
              </div>
            </div>

          </div>
          <?php
            if (!empty($formErrors)) {
              foreach($formErrors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
              }
            }
            if (isset($succesMsg)) {
              echo "<div class='alert alert-success'>$succesMsg</div>";
            }

           ?>
        </div>

      </div>
    </div>
  </div>

<?php
} else {
  header("location: login.php");
}
include $tpl . "footer.php";
 ?>
