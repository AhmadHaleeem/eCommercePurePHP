<?php
include "connect.php";

// Routes
$tpl = "includes/templates/";
$lang = "includes/languages/";
$func = 'includes/functions/';
$css = "layout/css/";
$js = "layout/js/";


// Include THe Important Files
include $func . 'functions.php';
include $lang . "english.php";
include $tpl . "header.php";

// Dont Incude The Navbar If There is Any Variable Has This Name($navBar)

if (!isset($navBar)) {
  include $tpl . 'myNavbar.php';
}
