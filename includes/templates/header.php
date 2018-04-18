<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?php getTitle() ?></title>

    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxItt.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>front.css" />
  </head>
<body>
  <div class="upper-div">
    <div class="container">
      <?php
      if (isset($_SESSION['user'])) {
        ?>
        <div class="btn-group my-info">
          <img class="img-circle"  src='test.jpg' />
          <span class="btn btn-default dropdown-toggle" data-toggle='dropdown'>
            <?php echo $sessionUser; ?>
            <span class="caret"></span>
          </span>
          <ul class="dropdown-menu">
            <li><a href='profile.php'>My Profile</a></li>
            <li><a href='newad.php'>New Item</a></li>
            <li><a href='logout.php'>Logout</a></li>
          </ul>
        </div>
        <?php
      } else {
       ?>
      <a href='login.php'>
        <span class='pull-right'>Login/Signup</span>
      </a>
      <?php } ?>

    </div>
  </div>
  <nav class="navbar navbar-inverse">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Homepage</a>
      </div>
      <div class="collapse navbar-collapse" id="app-nav">
        <ul class="nav navbar-nav navbar-right">
          <?php
          $getCats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID", "ASC");
          foreach($getCats as $cats) {
            echo "<li>
                    <a href='categories.php?do=" . $cats['ID'] ." '>" .  $cats['Name'] . "</a>
                 </li>";
          }
           ?>
        </ul>


          </li>
        </ul>
      </div>
    </div>
  </nav>
