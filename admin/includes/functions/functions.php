<?php
// Get Any Records functions
function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderField, $ordering = "DESC") {
  global $conn;
  $getAll = $conn->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
  $getAll->execute();
  $all = $getAll->fetchAll();
  return $all;
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
