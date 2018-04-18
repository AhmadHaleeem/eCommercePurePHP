<?php

// Get Any Records functions
function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderField, $ordering = "DESC") {
  global $conn;
  $getAll = $conn->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
  $getAll->execute();
  $all = $getAll->fetchAll();
  return $all;
}


/*
// Get Categories functions
function getCat() {
  global $conn;
  $getCat = $conn->prepare("SELECT * FROM `categories` ORDER BY ID ASC");
  $getCat->execute();
  $cats = $getCat->fetchAll();
  return $cats;
}
*/






/* Get Comments functions // I didnt use it but I Use Another Statment In profile.php in line number 67
function getComment($where, $value) {
  global $conn;
  $getComm = $conn->prepare("SELECT * FROM `comments` WHERE $where = ?");
  $getComm->execute(array($value));
  $comm = $getComm->fetchAll();
  return $comm;
}
*/

// Get items functions
function getItems($where, $value, $Approve = NULL) {
  if ($Approve == NULL) {
    $sql = 'AND Approve = 1';
  } else {
    $sql = '';
  }
  global $conn;
  $getItems = $conn->prepare("SELECT * FROM `items` WHERE $where = ? $sql ORDER BY Item_ID DESC");
  $getItems->execute(array($value));
  $items = $getItems->fetchAll();
  return $items;
}



// Check if the user is avtivate by the regStatus
function checkUserStatus($user) {
  global $conn;
  $stmtx = $conn->prepare("SELECT Username, RegStatus FROM `users` WHERE Username = ? AND RegStatus = 0 ");
  $stmtx->execute(array($user));
  $status = $stmtx->rowCount();
  return $status;
}














function getTitle() {
  global $pageTitle;
  if (isset($pageTitle)) {
    echo $pageTitle;
  } else {
    echo "Default";
  }
}

// Start Rediercted Page

    function redirectHome($theMsg, $url = null, $seconds = 4) {
      // Check If the User Come By Illegal Url
    if ($url === NULL) {
      $url = 'index.php';
      $link = 'Home Page';
    } else {
      if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
        $url = $_SERVER['HTTP_REFERER'];
        $link = 'Previous Page';
      } else {
        $url ='index.php';
        $link = 'Home Page';
      }
    }
    echo $theMsg;
  echo "<div class='alert alert-info'>" . ' You Will Redirected to ' . $link . ' After ' . $seconds . ' Seconds' . "</div>";
  header("refresh:$seconds;url=$url");
  exit();
}


// Check If the Items of username is Exist

function checkItem($select, $from, $value) {
  global $conn;
  $stmt = $conn->prepare("SELECT $select FROM $from WHERE $select = ?"); // This is Same ["SELECT Username FROM `users` WHERE Username = ? "]
  $stmt->execute(array($value)); // Execute The Result ..
  $count = $stmt->rowCount();
  return $count;

}

//Count Number Of Item Function V1.0
//Function To Count Number of Item Rows
//$item = The Item What I want To Count
//$table =  The Table To Choose from

function countItems($item, $table) {
  global $conn;
  $stmt2 = $conn->prepare("SELECT COUNT($item) FROM $table");
  $stmt2->execute();
  return $stmt2->fetchColumn();

}
// Get The Latest Records Function V1.0
// Function To Get The latest Items From Database [Users,Items,Comments]
// $select = Field To Select
// $table = Table To select
// $order = The Order By [userid, Email,.....]
// $limit = Number rOf Records To Get..

function getLatest($select, $table,$order, $LIMIT = 5) {
  global $conn;
  $getStmt = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $LIMIT");
  $getStmt->execute();
  $row = $getStmt->fetchAll();
  return $row;
}
