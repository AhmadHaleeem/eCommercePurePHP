<?php
function lang($phrase) {
  static $lang = array(
    // Navbar Links
    "home_admin" => "Home",
    "Categories" => "Categories",
    "ITEMS"      => "Items",
    "MEMBERS"    => "Members",
    "COMMENTS"    => "Comments",
    "STATISTICS" => "Statistics",
    "LOGS"       => "Logs"
  );
  return $lang[$phrase];
}


 ?>
