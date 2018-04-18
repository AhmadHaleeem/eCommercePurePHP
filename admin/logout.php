<?php

session_start();
// Empty The Session From The Data
session_unset();
// Destroy The Session
session_destroy();
// Transform The Page To index Page
header('location: index.php');
// Exit From The Header 
exit();
