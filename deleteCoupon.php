<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'cjk5807';
$password = 'password';
$host = 'e5-cse-cs431fa21s1-39';
$dbname = 'cjk5807_431W';

?>

<!DOCTYPE html>
<html>
   <head>
        <title>Account Manager</title>
   </head>
   <body style="background-color: antiquewhite">
   <p><?php
      if (!empty($_POST["user_id"]) and !empty($_POST["coupon_id"])) {
        $user_id = $_POST["user_id"];
        echo "Deleting coupon: " . $_POST["coupon_id"] . "..."; 
        $sql1 = 'DELETE FROM cjk5807_coupon WHERE coupon_id = "' . $_POST["coupon_id"] . '"';
        $sql2 = 'DELETE FROM cjk5807_store2coupon WHERE coupon_id = "' . $_POST["coupon_id"] . '"';
        try {
					$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$conn->exec($sql1);
          $conn->exec($sql2);
					echo "<br> Coupon deleted successfully";
				?>
				<p>You will be redirected in 3 seconds</p>
				<script>
          var username = "<?php echo $user_id; ?>";
			    var timer = setTimeout(function() {window.location.href='index.php?username=' + username}, 3000);
					
				</script>
			 <?php
				} catch(PDOException $e) {
					echo $sql1 . "<br>" . $sql2 . "<br>" . $e->getMessage();
				}
				$conn = null;
      }
    else
      {
      ?>
       <p> User deleted unsuccessfully </p>
       <script>
          var timer = setTimeout(function() {
						window.location='index.php'
					}, 3000);
       </script>
      <?php
      }
  ?></p>
</div>
</html>
