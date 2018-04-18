<?php
    session_start();
    $pageTitle = 'My Profile';
    include "init.php";
    if (isset($_SESSION['user'])) {
      $stmt = $conn->prepare("SELECT * FROM `users` WHERE Username = ?");
      $stmt->execute(array($sessionUser));
      $info = $stmt->fetch();
      $userID = $info['UserID'];
    ?>
    <h1 class="text-center"><?php echo $pageTitle; ?></h1>
      <div class='information block'>
        <div class="container">
          <div class="panel panel-primary">
            <div class="panel-heading">Heading</div>
            <div class="panel-body">
              <ul class="list-unstyled">
                <li>
                  <i class ='fa fa-unlock-alt fa-fw'></i>
                  <span>Login Name </span> : <?php echo $info['Username'] ?>
                 </li>
                <li>
                  <i class ='fa fa-envelope-o fa-fw'></i>
                  <span>Email </span> : <?php echo $info['Email'] ?>
                </li>
                <li>
                  <i class ='fa fa-user fa-fw'></i>
                  <span>Fullname </span> : <?php echo $info['FullName'] ?>
                </li>
                <li>
                  <i class ='fa fa-calendar fa-fw'></i>
                  <span>Register Date </span> : <?php echo $info['Datee'] ?>
                </li>
                <li>
                  <i class ='fa fa-tags fa-fw'></i>
                  <span>Favourite Category</span>
                </li>
              </ul>
              <a href='#' class="btn btn-default">Edit Information</a>
            </div>
          </div>
        </div>
      </div>





      <div class='ads block'>
        <div class="container">
          <div class="panel panel-primary">
            <div class="panel-heading">My Items</div>
            <div class="panel-body">

              <?php
                $myItems = getAllFrom("*", "items", "WHERE Member_ID = $userID", "", "Item_ID");
                if (!empty($myItems)) {
                  echo "<div class='row'>";
                    foreach($myItems as $item) {
                      echo "<div class='col-sm-6 col-md-3'>";
                        echo "<div class='thumbnail item-box'>";
                        if ($item['Approve'] == 0) {
                          echo "<span class='approve-status'>Waiting Approval</span>";
                        }
                          echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
                          echo "<img class='img-responsive' src='test.jpg' alt='' />";
                          echo "<div class='caption'>";
                            echo "<h3><a href='items.php?itemid=" . $item['Item_ID'] . "'>" . $item['Name'] . "</a></h3>";
                            echo "<p>" . $item['Description'] . "</p>";
                            echo "<div>" . $item['Add_Date'] . "</div>";
                          echo "</div>";
                        echo "</div>";
                      echo "</div>";
                    }
                  echo "</div>";
                } else {
                  echo "Theres No Ads To Show.., Create <a href='newad.php'>New Ads</a>";
                }

               ?>

            </div>
          </div>
        </div>
      </div>





      <div class='my-comments block'>
        <div class="container">
          <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">


                <?php

                  $comments= getAllFrom("comment", "comments", "WHERE user_id = $userID", "", "c_id");

                    if (!empty($comments)) {
                      echo "<div class='row'>";
                        foreach($comments as $comment) {
                          echo "<p class='one'>" . $comment['comment'] . "</p>";
                        }
                      echo "</div>";
                    } else {
                      echo "Theres No Comment To Show..";
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
