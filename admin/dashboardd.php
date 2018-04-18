<?php
  session_start();
  if (isset($_SESSION['Username'])) {
    $pageTitle = 'Dashboard';
    include "init.php";
    $numUsers = 6; // Number of The Latest user
    $theLatestUsers = getLatest('*', 'users', 'UserID', $numUsers); // latest user Array
    $numItems = 6;
    $theLatestItems = getLatest('*', 'items', 'Item_ID', $numItems);
     ?>

    <!-- Start Desgin Dashboard page -->

    <div class="container text-center home-stats">
      <h1>Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat st-members">
            <i class='fa fa-users'></i>
            <div class="info">
              Total Members
              <span><a href='members.php'><?php echo countItems('UserID', 'users') ?></a></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-pending">
            <i class='fa fa-plus'></i>
            <div class="info">
              Pending Members
              <span>
                <a href='members.php?do=Manage&page=pending'>
                <?php echo checkItem('RegStatus', 'users', 0) ?>
                </a>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-items">
            <i class='fa fa-tag'></i>
            <div class="info">
              Total Items
              <span><a href='items.php'><?php echo countItems('Item_ID', 'items') ?></a></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat st-comments">
            <i class='fa fa-comment'></i>
            <div class="info">
              Total Comments
              <span><a href='comments.php'><?php echo countItems('c_id', 'comments') ?></a></span>
            </div>
          </div>
        </div>
      </div>
    </div>


      <div class="container latest">
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-users"></i><?php echo " Latest $numUsers Registerd Users" ?>
                <span class="toggle-info pull-right">
                  <i class="fa fa-plus fa-lg"></i>
                </span>
              </div>
              <div class="panel-body">
                <ul class="list-unstyled latest">
                <?php
                if (!empty($theLatestUsers)) {
                  foreach($theLatestUsers as $user) {
                    echo "<li>";
                      echo $user['Username'];
                      echo "<a href='members.php?do=Edit&UserID=" . $user['UserID'] . " ' > ";
                        echo "<span class='btn btn-success pull-right'>";
                          echo "<i class='fa fa-edit'></i> Edit";
                          if ($user['RegStatus'] == 0) {
                            echo "<a href='members.php?do=Activate&UserID=" . $user['UserID'] . " ' class='btn btn-info pull-right'><i class='fa fa-check'></i>Activate</a>";
                          }
                    	echo "</span>";
                      echo "</a>";
                    echo "</li>";

                  }
                } else {
                    echo "There Are No Users To Show";
                }
                 ?>
                 </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-tag"></i> <?php echo "Latest $numItems Items";  ?>
                <span class="toggle-info pull-right">
                  <i class="fa fa-plus fa-lg"></i>
                </span>
              </div>
              <div class="panel-body">
                <ul class="list-unstyled latest">
                  <?php
                  if (!empty($theLatestItems)) {
                    foreach($theLatestItems as $item) {
                      echo "<li>";
                        echo $item['Name'];
                        echo "<a href='items.php?do=Edit&Item_ID=" . $item['Item_ID'] ."'> ";
                        echo "<span class='btn btn-success pull-right'>";
                        echo "<i class='fa fa-edit'></i> Edit";
                        if ($item['Approve'] == 0) {
                          echo "<a href='items.php?do=Approve&Item_ID=" . $item['Item_ID'] . "' class='btn btn-info pull-right'><i class='fa fa-check'></i> Approve </a>";
                        }
                        echo "</span>";
                        echo "</a>";

                      echo "</li>";
                    }
                } else {
                  echo "There Are No Items To Show";
                }

                   ?>
                </ul>
              </div>
            </div>
          </div>
        </div>


        <!-- Start The Latest Comments -->
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-comments-o"></i> Latest Comments
                <span class="toggle-info pull-right">
                  <i class="fa fa-plus fa-lg"></i>
                </span>
              </div>
              <div class="panel-body">
                <?php
                $stmt = $conn->prepare("SELECT comments.*, users.Username AS MemberName FROM `comments`
                                          INNER JOIN users ON users.UserID = comments.user_id  ORDER BY c_id DESC");
                $stmt->execute();
                $comments = $stmt->fetchAll();
                if (!empty($comments)) {
                  foreach($comments as $comment) {
                    echo "<div class='comment-box'>";
                    echo "<span class='member-n'>
                            <a href='members.php?do=Edit&UserID = " . $comment['user_id'] . "'>" . $comment['MemberName'] . "</a>
                          </span>";
                      echo "<p class='member-c'>"  . $comment['comment'] .  "</p>";
                    echo "</div>";
                  }

                } else {
                  echo "There Are No Comments To Show";
                }
                 ?>
              </div>
            </div>
          </div>
        </div>
        <!-- End The Latest Comments -->
      </div>

    <!-- End Desgin Dashboard page -->
    <?php
    include $tpl . "footer.php";
  } else {
    header("location: index.php");
    exit();
  }

 ?>
