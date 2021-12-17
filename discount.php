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
        <title>Apply Discounts</title>
   </head>
   <body style="background-color: antiquewhite">
   <p><?php
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          echo 'Applying coupon ' . $_POST["coupon_id"] . ' to user "' . $_POST["user_id"] . '" with coupon "' . $_POST["saving"] . '"';
          $sql = 'UPDATE cjk5807_account SET total_saving = ROUND(total_saving + "' . $_POST["saving"] . '",2) WHERE user_id = "' . $_POST["user_id"] . '"';
		      $sqlItemInsert = 'INSERT INTO cjk5807_usedCoupon (user_id, coupon_id) ';
		      $sqlItemInsert = $sqlItemInsert . 'VALUES ("' . $_POST["user_id"] . '","' . $_POST["coupon_id"] . '")';
          $user_id = $_POST["user_id"];
          try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    				$conn->exec($sql);
				  	$conn->exec($sqlItemInsert);
    				echo "<br> Recorded successfully";
    			?>
    				<script>
              var username = "<?php echo $user_id; ?>";
    					var timer = setTimeout(function() {
    						window.location.href='index.php?username=' + username
    					}, 10);
    				</script>
    			<?php
    				} catch(PDOException $e) {
            			echo $sql . "<br>" . $e->getMessage();
						}
	   }
  ?></p>
</div>
</html>
