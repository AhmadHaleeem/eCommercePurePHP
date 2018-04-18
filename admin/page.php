<?php


 $do = '';
/// if the Request has Name (do) Then Print Inside the Variable [$_GET['do']];
 if (isset($_GET['do'])) {
   $do = $_GET['do'];
 } else {
   $do = 'Manage';
 }

 if ($do == 'Manage') {
   echo "Welcome To Manage Category Page.. ";
   // Convert Me to Add Page ..
   echo "<a href='page.php?do=Add'>Add Any Item</a>";
 } elseif ($do == 'Add') {
   echo "Welcome To Add Category Page..";
 } elseif ($do == 'Insert') {
   echo "Welcome To Insert Category Page..";
 } else {
   echo "Error There Are No Category Page..";
 }


 
