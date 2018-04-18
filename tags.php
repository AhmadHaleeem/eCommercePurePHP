<?php include "init.php";
  $pageTitle = 'Tags';
 ?>

<div class='container'>

  <div class="row">
  <?php
  if (isset($_GET['name'])) {
    $tag = $_GET['name'];
    echo "<h1 class='text-center'>" . $tag . "</h1>";

    $getItems = getAllFrom("*", "items", "WHERE Tags LIKE '%$tag%'", "AND Approve = 1", "Item_ID");
    foreach($getItems as $item) {
      echo "<div class='col-sm-6 col-md-3'>";
        echo "<div class='thumbnail item-box'>";
          echo "<span class='price-tag'>" . $item['Price'] . "</span>";
          echo "<img class='img-responsive' src='test.jpg' lat='' />";
          echo "<div class='caption'>";
            echo "<h3><a href='items.php?itemid=" . $item['Item_ID'] ."'>" . $item['Name'] . "</a></h3>";
            echo "<p>" . $item['Description'] . "</p>";
            echo "<span class='add-date'>" . $item['Add_Date'] . "</span>";
          echo "</div>";
        echo "</div>";
      echo "</div>";
    }

  } else {
    echo "<div class='container'>";
      echo "<div class='alert alert-danger'>Sorry You Must Write Tag Name</div>";
    echo "</div>";
  }
   ?>
   </div>
</div>


<?php include $tpl . "footer.php";?>
