<?php
session_start();
if (isset($_SESSION['Username'])) {
  $pageTitle = '';
  include "init.php";

  if (isset($_GET['do'])) {
  $do = $_GET['do'];
  } else {
  $do = 'Manage';
  }

  if ($do == 'Manage') { // Manage Page

  }

  elseif ($do == 'Add') { // Add Page

  }

  elseif ($do == 'Insert') { // Insert Page

  }

  elseif ($do == 'Edit') { // Edit Page

  }

  elseif ($do == 'Update') { // Update Page

  }

  elseif ($do == 'Delete') { // Delete Page

  }

  elseif ($do == 'Activate') { // Activate Page

  }
  include $tpl . "footer.php";
} else {
  header("location: index.php");
  exit();
}
