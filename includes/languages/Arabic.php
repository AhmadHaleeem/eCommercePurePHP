<?php

function lang($phrase) {
  static $lang = array(
    "Message" => "السلام عليكم",
    "Admin"   => "احمد"
  );
  return $lang[$phrase];
}

 ?>
