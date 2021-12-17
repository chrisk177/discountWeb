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
   <p>
	   <?php
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         if (!empty($_POST["fname"]) and !empty($_POST["lname"]) and !empty($_POST["password"]) and  !empty($_POST["email"]) and
		 !empty($_POST["username"]) and !empty($_POST["state"]) and !empty($_POST["city"])) {
            echo "Inserting new user: " . $_POST["state"] . " " . $_POST["city"] . " " . $_POST["username"] . "...";
            $sqlSelect = '(SELECT location_id FROM cjk5807_location WHERE city = "' . $_POST["city"] . '" AND (state_id = "' . $_POST["state"] . '" OR state_name = "' . $_POST["state"] . '"))';
            $sqlInsert = 'INSERT INTO cjk5807_account (user_id , password, fname, lname, location_id, total_saving, email) ';
            $sqlInsert = $sqlInsert . 'VALUES ("'.$_POST["username"] . '","' . $_POST["password"] . '","' . $_POST["fname"] . '","' . $_POST["lname"] . '",' . $sqlSelect . ',"0","' . $_POST["email"] . '")';
            $user_id = $_POST["username"];
         try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->beginTransaction();
    				$conn->exec($sqlInsert);
            $conn->commit();
    				echo "<br> New record created successfully";
    			?>
    				<p>You will be redirected in 3 seconds</p>
    				<script>
              var username = "<?php echo $user_id; ?>";
    					var timer = setTimeout(function() {
    						window.location.href='index.php?username=' + username
    					}, 3000);
    				</script>
    			<?php
    				} catch(PDOException $e) {
              		  	$conn->rollBack();
						if ($e->errorInfo[1] == 1062) {
		    			?>
		    				<script>
					            var username = "<?php echo $user_id; ?>";
				    			alert('Error: The username ""' + username + '" is already in use');
		    					var timer = setTimeout(function() {
		    						window.location.href='index.php?error=error'
		    					}, 1);
		    				</script>
		    			<?php
						} else
						{
							echo $sqlInsert . "<br>" . $e->getMessage();
						}
						
    				}
    				$conn = null;
          }
          else
          {
    			?>
       <p>Something went wrong </p>
       <script>
    					var timer = setTimeout(function() {
    						window.location='index.php'
    					}, 3000);
      </script>
      <?php
      }
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
      if (!empty($_GET["username"]) and !empty($_GET["password"])) {
        $sqlSelect = 'SELECT * FROM cjk5807_account WHERE user_id = "' . $_GET["username"] . '" AND password = "' . $_GET["password"] . '"';
        try {
          $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
          $q = $pdo->query($sqlSelect);
          $q->setFetchMode(PDO::FETCH_ASSOC);
          $row = $q->fetch();
          if ($row) {
  				  echo "<br> " . $row["user_id"] . " retrieved successfully";
          }
          else {
           echo "<br> retrieved unsuccessfully";
          }
			  ?>
				<p>You will be redirected in 2 seconds</p>
				<script>
          var username = "<?php echo $row["user_id"]; ?>";
					var timer = setTimeout(function() {
						window.location.href='index.php?username=' + username
					}, 2000);
				</script>
			  <?php
				} catch(PDOException $e) {
          $conn->rollBack();
					echo $sqlInsert . "<br>" . $e->getMessage();
				}
				$conn = null;
      }
      else
      { ?>
       <p>Something went wrong</p>
       <script>
    					var timer = setTimeout(function() {window.location='index.php'}, 2000);
      </script>
      <?php
      }
    }
  ?></p>
</div>
</html>
