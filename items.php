<?php
session_start();
$pageTitle = 'Show Items';
include "init.php";

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

$stmt = $conn->prepare("SELECT items.*, categories.Name AS category_name, users.Username AS Username FROM `items`
                          INNER JOIN categories ON categories.ID = items.Cat_ID
                          INNER JOIN users ON users.UserID = items.Member_ID
                          WHERE Item_ID = ? AND Approve = 1");
$stmt->execute(array($itemid));
$row = $stmt->rowCount();
if ($row > 0) {
$item = $stmt->fetch();
?>

<h1 class="text-center"><?php echo $item['Name'] ?></h1>
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <img class='img-responsive img-thumbnail' src='test.jpg' lat='' />
    </div>

    <div class="col-md-9 item-info">
        <h2><?php echo $item['Name'] ?></h2>
        <p><?php echo $item['Description'] ?></p>
      <ul class="list-unstyled">
        <li>
          <i class="fa fa-calendar-o fa-fw"></i>
          <span>Added Date</span> : <?php echo $item['Add_Date'] ?>
        </li>
        <li>
          <i class="fa fa-money fa-fw"></i>
          <span>Price</span> : <?php echo "$" . $item['Price'] ?>
        </li>
        <li>
          <i class="fa fa-building-o fa-fw"></i>
          <span>Made In</span> : <?php echo $item['Country_Made'] ?>
        </li>
        <li>
          <i class="fa fa-tags fa-fw"></i>
          <span>Category</span> : <a href='categories.php?do=<?php echo $item['Cat_ID'] ?>'><?php echo $item['category_name'] ?></a>
        </li>
        <li>
          <i class="fa fa-user fa-fw"></i>
          <span>Added By</span> : <a href='#'><?php echo $item['Username'] ?></a>
        </li>
        <li>
          <i class="fa fa-tags fa-fw"></i>

          <span>Tags</span> :
          <?php
          $allTags = explode(",", $item['Tags']);

            foreach($allTags as $tag) {
              $tag = str_replace(" ", "", $tag);
              $lowerTag = strtolower($tag);
              if (!empty($tag)) {
              echo "<a href='tags.php?name=" . $lowerTag . "'>" . $lowerTag . "</a>";
            }
          }
           ?>

        </li>
      </ul>


    </div>
  </div>
  <hr class="custom-hr">
  <?php
    if (isset($_SESSION['user'])) {
   ?>
  <div class="row">
    <div class="col-md-offset-3">
      <h3>Add Your Comment</h3>
      <div class="add-comment">
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?itemid=" . $item['Item_ID'] ?>" method="post">
          <textarea name="comment" required></textarea>
          <input class="btn btn-primary" type="submit" value="Add Comment" />
        </form>
        <?php
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
            $itemid  = $item['Item_ID'];
            $userid  = $_SESSION['uid'];

            if (!empty($comment)) {
              $stmt = $conn->prepare("INSERT INTO `comments`(comment, status, comment_date, item_id, user_id) VALUES(:zcom, 0, Now(), :zitem, :zuser)");
              $stmt->execute(array(
                'zcom'  => $comment,
                'zitem' => $itemid,
                'zuser' => $userid
              ));
              if ($stmt) {
                echo "<div class='alert alert-success'>Comment Added</div>";
              }
            } else {
                echo "<div class='alert alert-danger'>Sorry You Cant Add an Empty Comment </div>";
            }

          }
         ?>
      </div>
    </div>
  </div>
<?php } else {
  echo "You Cat See The Comments Please <a href='login.php'>Login</a> or <a href='login.php'>Register</a> To Show";
} ?>
  <hr class="custom-hr">
    <?php
      $stmtComm = $conn->prepare("SELECT comments.*, users.Username AS Member FROM `comments`
                                  INNER JOIN users ON users.UserID = comments.user_id WHERE item_id = ? AND status = 1 ORDER BY c_id DESC");
      $stmtComm->execute(array($item['Item_ID']));

      $comments = $stmtComm->fetchAll();

          foreach($comments as $comment) {?>
            <div class="comment-box">
              <div class='row'>
                <div class='col-sm-2 text-center'>
                  <img class='img-responsive img-circle center-block' src='test.jpg' lat='' />
                  <?php echo $comment['Member']?>
                </div>
                <div class='col-sm-10'>
                  <p class="lead">
                    <?php echo $comment['comment']?>
                  </p>
                </div>
              </div>
            </div>
            <hr class="custom-hr"/>
          <?php
          }

         ?>
</div>

<?php
} else {
  echo "<div class='container'>";
    echo "<div class='row'>";
      echo "<div class='alert alert-danger'>There is No Such As This ID or This Items Waiting Approval</div>";
    echo "</div>";
  echo "</div>";
}
include $tpl . "footer.php";
 ?>
