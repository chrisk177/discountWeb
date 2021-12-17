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
      if (!empty($_POST["user_id"])) {
        echo "Deleting user: " . $_POST["user_id"] . "..."; 
        $sql = 'DELETE FROM cjk5807_account WHERE user_id = "' . $_POST["user_id"] . '"';
        try {
					$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$conn->exec($sql);
					echo "<br> User deleted successfully";
				?>
				<p>You will be redirected in 3 seconds</p>
				<script>
					var timer = setTimeout(function() {
						window.location='index.php'
					}, 3000);
				</script>
			 <?php
				} catch(PDOException $e) {
					echo $sql . "<br>" . $e->getMessage();
				}
				$conn = null;
      }
    else
      {
      ?>
       <p> User deleted unsuccessfully </p>
       <script>
			    var timer = setTimeout(function() {window.location='index.php'}, 100);
       </script>
      <?php
      }
  ?></p>
</div>
</html>
